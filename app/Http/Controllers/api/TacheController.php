<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Projet;
use App\Models\Tache;
use App\Models\User;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use App\Notifications\UserInvitedNotification;

class TacheController extends Controller
{
    public function assignTaskToUser(Request $request, int $project_id)
    {
        
        $request->validate([
            'titre' => 'required',
            'id_assigne' => 'required',
            'description' => 'required',
            'priorite' => 'required',
            'date_echeance' => 'required|date|after:now',
        ]);


        $project = Projet::findOrFail($project_id);

        $task = Tache::create([
            
            'titre' => $request->titre,
            'id_assigne' => $request->id_assigne,
            'description' => $request->description,
            'priorite' => $request->priorite,
            'date_echeance' => $request->date_echeance,
            'id_projet' => $project_id,
        ]);

    
        $assignedUser = User::findOrFail($request->id_assigne);
        $assignedUser->notify(new UserInvitedNotification($project, $task->titre));
        return response()->json($task, 201);
    }

    
    public function decommissionTaskToUser(int $user_id, int $task_id)
    {
        $task = Tache::find($task_isd);
        if ($task->projet()->id_responsable == auth()->user()->id) {
            $task->user->id_assigné = null;
            $task->save();
            return response()->json($task, 201);
        } else {
            return response()->json(['message' => 'Permission denied'], 403);
        }
    }

    public function deleteTask(int $task_id)
    {
        $task = Tache::find($task_id);
        if ($task->projet()->id_responsable == auth()->user()->id) {
            $task->delete();
            return response()->json($task, 200);
        } else {
            return response()->json(['message' => 'Permission denied'], 403);
        }
    }

    public function changeStatus(Request $request, int $id)
    {
        $task = Tache::find($id);
        $request->validate([
            'etat' => 'required',
        ]);
        if ($task->projet->id_responsable == auth()->user()->id || $task->id_assigne == auth()->user()->id) {
            $task->etat = $request->etat;
            $task->save();
            return response()->json($task, 200);
        } else {
            return response()->json(['message' => 'Permission denied'], 403);
        }
    }

    public function changePriority(Request $request, int $id)
    {
        $task = Tache::find($id);
        $request->validate([
            'priorite' => 'required',
        ]);
        if ($task->projet()->id_responsable == auth()->user()->id) {
            $task->priorite = $request->priorite;
            $task->save();
            return response()->json($task, 200);
        } else {
            return response()->json(['message' => 'Permission denied'], 403);
        }
    }

    public function addComment(Request $request, int $id)
    {

        $task = Tache::findOrFail($id);
    
        $request->validate([
            'texte' => 'required',
        ]);

        $commentaire = new Commentaire([
            'texte' => $request->texte,
            'id_utilisateur' => auth()->user()->id,
            'date' => now(),
        ]);
    
        $task->commentaires()->save($commentaire);
        return response()->json($commentaire->load('user'), 201);
    }
    
    public function editComment(Request $request, int $comment_id)
    {
        $commentaire = Commentaire::find($comment_id);
        $commentaire->update($request->all());
        return response()->json($commentaire->load('user'), 200);
    }

    public function getAllComments(int $id)
    {
        $task = Tache::find($id);
        return response()->json($task->commentaires, 200);
    }

    public function deleteComment(int $comment_id)
    {
        $commentaire = Commentaire::find($comment_id);
        if ($commentaire->utilisateur_id == auth()->user()->id) {
            $commentaire->delete();
            return response()->json($commentaire->load('user'), 200);
        } else {
            return response()->json(['message' => 'Permission denied'], 403);
        }
    }


    public function addAttachment(Request $request, int $id)
    {
        $task = Tache::find($id);
        $request->validate([     
            'nom_fichier' => 'required',                                                                                  
            'chemin_fichier' => 'required|file|max:1024',
        ]);
        $file = $request->file('file');
        $path = public_path('uploads/taches/' . $file->getClientOriginalName());
        $file->move($path);
        $task->pieces_jointes()->attach($path);
        return response()->json($task, 201);
    }

    public function deleteAttachment(int $id, int $attachment_id)
    {
        $attachment = Attachment::find($attachment_id);
        if ($attachment->tache->id_responsable == auth()->user()->id) {
            $attachment->delete();
            return response()->json($attachment, 200);
        } else {
            return response()->json(['message' => 'Permission denied'], 403);
        }
    }
    public function getTaskByProjet(int $id)    
    {
        $projet = Projet::find($id);
        return response()->json($projet->taches()->with('user', 'commentaires.user', 'pieces_jointes','projet')->get());
    }


    public function countTaskByStatus(int $id)
    {
        $projet = Projet::find($id);
        $tasksCount = $projet->taches()
            ->selectRaw('etat, COUNT(*) as count')
            ->groupBy('etat')
            ->get();
        return response()->json($tasksCount);
    }

    public function getTaskById(int $id)
    {
        $task = Tache::where('id', $id)->first();
        return response()->json($task->load('commentaires.user', 'pieces_jointes','user','projet'));
    }

    public function editTask(Request $request, int $id)
    {
        $task = Tache::find($id);
        $task->update($request->all());
        return response()->json($task->load('commentaires.user', 'pieces_jointes','user','projet'), 200);
    }


}
