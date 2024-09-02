<?php

namespace App\Http\Controllers\api;

use App\Events\UserInvited;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Projet;
use App\Models\Tache;
use App\Models\User;
use App\Notifications\UserInvitedNotification;
use Illuminate\Http\Request;


class ProjetController extends Controller
{
    public function inviteUser(Request $request, int $id)
    {
        $user = User::where('email', $request->input('email'))->firstOrFail();
        $project = Projet::findOrFail($id);

        $invitation = new Invitation([
            'id_utilisateur' => $user->id,
            'status' => 'en attente',
        ]);
        
        $invitation->id_projet = $project->id;
        $invitation->save();
        $user->notify(new UserInvitedNotification($project, $invitation));
        return response()->json($project, 201);
    }

    public function getUserInProject(int $id)
    {
        $project = Projet::find($id);
        if (!$project) {
            return response()->json(['error' => 'Projet non trouvé'], 404);
        }

        $users = $project->invitation()
            ->where('status', 'accepter')
            ->with('user')
            ->get();
        return response()->json($users);
    }



    public function acceptInvitation(int $id)
    {
        $invitation = Invitation::find($id);
        $invitation->update(['status' => 'accepter']);
        return response()->json(['message' => 'Invitation accepted'], 200);
    }

    public function declineInvitation(int $id)
    {
        $invitation = Invitation::find($id);
        $invitation->update(['status' => 'refuser']);

        return response()->json(['message' => 'Invitation declined']);
    }

    public function index()
    {
        $myAllProjet = Projet::with(['invitation' => function ($query) {
                $query->where('status', 'accepter')->with('user');
            }])
            ->where('id_responsable', auth()->user()->id)
            ->get();
    
        $myAllProjet->each(function ($projet) {
            $totalTasks = $projet->taches()->count();
            $completedTasks = $projet->taches()->where('etat', 'termine')->count();
            $projet->progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
        });
    
        return response()->json($myAllProjet);
    }
    
    

    public function show(Projet $projet)
    {
        return response()->json($projet);
    }

    public function store(Request $request)
    {
        $data =  $request->validate([

            'nom' => 'required',
            'description' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
        ]);

        $projet = auth()->user()->projets()->create($data);
        return response()->json($projet, 201);
    }

    public function update(Request $request, int $id)
    {
        $projet = Projet::find($id);
        $request->validate([
            'nom' => 'required',
            'description' => 'required',
            'date_debut' => 'required|date|after:now',
            'date_fin' => 'required|date|after:date_debut',
        ]);
        $projet->update($request->all());
        return response()->json($projet, 200);
    }

    public function countTasks(int $id)
    {
        $projet = Projet::find($id);
        return response()->json($projet->taches()->count());
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

    public function countAllProjet()
    {
        $projetCount = Projet::where('id_responsable', auth()->user()->id)->count();
        return response()->json($projetCount);
    }

    public function getProjectProgress(Projet $projet)
    {
        $totalTasks = $projet->taches()->count();
        $completedTasks = $projet->taches()->where('etat', 'termine')->count();
        $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
        return response()->json(['progress' => $progressPercentage]);
    }
    

    public function countAllProjetByStatus()
    {
        $projets = Projet::selectRaw('etat, COUNT(*) as count')
            ->where('id_responsable', auth()->user()->id)
            ->groupBy('etat')
            ->get();
        return response()->json($projets);
    }


    public function destroy(Projet $projet)
    {
        $projet->delete();
        return response()->json($projet);
    }

    public function setStatus(Request $request, int $id)
    {
        $projet = Projet::find($id);
        $request->validate([
            'etat' => 'required',
        ]);
        if ($projet->id_responsable == auth()->user()->id) {
            $projet->etat = $request->etat;
            $projet->save();
            return response()->json($projet, 200);
        } else {
            return response()->json(['message' => 'Permission denied'], 403);
        }
    }
    public function getMyAssignedProjects()
    {
        $userId = auth()->user()->id;
    
        $projets = Projet::whereHas('invitation', function ($query) use ($userId) {
                $query->where('id_utilisateur', $userId);
            })
            ->with(['invitation.user'])
            ->get();

           $projets->each(function ($projet) {
                $totalTasks = $projet->taches()->count();
                $completedTasks = $projet->taches()->where('etat', 'termine')->count();
                $projet->progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
            });
    
        return response()->json($projets);
    }
    
    

}
