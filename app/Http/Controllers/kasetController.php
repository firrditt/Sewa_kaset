<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KasetService;

class KasetController extends Controller
{
    protected $kasetService;

    public function __construct(KasetService $kasetService)
    {
        $this->kasetService = $kasetService;
    }

    public function index()
    {
        return response()->json($this->kasetService->getAll());
    }

    public function show($id)
    {
        return response()->json($this->kasetService->getById($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'genre' => 'required|string',
            'year' => 'required|integer',
        ]);

        return response()->json($this->kasetService->create($validated));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'genre' => 'sometimes|required|string',
            'year' => 'sometimes|required|integer',
        ]);

        return response()->json($this->kasetService->update($id, $validated));
    }

    public function destroy($id)
    {
        $this->kasetService->delete($id);
        return response()->json(null, 204);
    }
}
