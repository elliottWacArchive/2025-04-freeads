<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('verified')->except(['index', 'show']);
    }
    public function index(Request $request)
    {
        $query = Annonce::with('images');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('categorie') && !empty($request->categorie)) {
            $query->where('categorie', $request->categorie);
        }
        
        if ($request->has('prix_min') && is_numeric($request->prix_min)) {
            $query->where('prix', '>=', $request->prix_min);
        }
        
        if ($request->has('prix_max') && is_numeric($request->prix_max)) {
            $query->where('prix', '<=', $request->prix_max);
        }
        
        if ($request->has('date_min') && !empty($request->date_min)) {
            $query->where('created_at', '>=', $request->date_min);
        }
        
        $order = $request->has('order') ? $request->order : 'latest';
        
        switch ($order) {
            case 'price_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $annonces = $query->paginate(10)->withQueryString();
        
        $categories = Annonce::distinct('categorie')->pluck('categorie')->filter();
        
        return view('annonces.index', compact('annonces', 'categories'));
    }
    
    public function create()
    {
        return view('annonces.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie' => 'nullable|string|max:50',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'prix' => 'required|numeric|min:0',
        ]);
    
        $annonce = new Annonce([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'categorie' => $validated['categorie'] ?? null,
            'prix' => $validated['prix'],
            'user_id' => Auth::id()
        ]);

        $annonce->save();

        // Traitement des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $path = $image->store('annonces', 'public');
                
                $isPrimary = ($key === 0);
                
                Image::create([
                    'annonce_id' => $annonce->id,
                    'path' => $path,
                    'is_primary' => $isPrimary
                ]);
            }
        }

        return redirect()->route('annonces.show', $annonce->id)
            ->with('success', 'Annonce créée avec succès');
    }
    
    public function show(Annonce $annonce)
    {
        $annonce->load('images');
        return view('annonces.show', compact('annonce'));
    }
    
    public function edit(Annonce $annonce)
    {
        if ($annonce->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée');
        }
        
        $annonce->load('images');
        return view('annonces.edit', compact('annonce'));
    }
    
    public function update(Request $request, Annonce $annonce)
    {
        if ($annonce->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'new_images' => 'sometimes|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'prix' => 'required|numeric|min:0',
            'primary_image' => 'sometimes|integer|exists:images,id',
            'delete_images' => 'sometimes|array',
            'delete_images.*' => 'integer|exists:images,id',
        ]);

        $annonce->titre = $validated['titre'];
        $annonce->description = $validated['description'];
        $annonce->prix = $validated['prix'];
        $annonce->save();

        if ($request->has('delete_images') && is_array($request->delete_images)) {
            foreach ($request->delete_images as $imageId) {
                $image = Image::find($imageId);
                if ($image && $image->annonce_id === $annonce->id) {
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    $image->delete();
                }
            }
        }

        if ($request->has('primary_image')) {
            $annonce->images()->update(['is_primary' => false]);
            
            $annonce->images()->where('id', $request->primary_image)->update(['is_primary' => true]);
        }

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('annonces', 'public');
                
                $isPrimary = false;
                if (!$annonce->images()->where('is_primary', true)->exists()) {
                    $isPrimary = true;
                }
                
                Image::create([
                    'annonce_id' => $annonce->id,
                    'path' => $path,
                    'is_primary' => $isPrimary
                ]);
            }
        }

        return redirect()->route('annonces.show', $annonce->id)
            ->with('success', 'Annonce mise à jour avec succès');
    }
    
    public function destroy(Annonce $annonce)
    {
        if ($annonce->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée');
        }

        foreach ($annonce->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }
        
        if ($annonce->photo && Storage::disk('public')->exists($annonce->photo)) {
            Storage::disk('public')->delete($annonce->photo);
        }

        $annonce->delete();

        return redirect()->route('annonces.index')
            ->with('success', 'Annonce supprimée avec succès');
    }
}