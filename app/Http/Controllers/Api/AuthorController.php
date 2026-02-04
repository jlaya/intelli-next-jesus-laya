<?php

namespace App\Http\Controllers\Api;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Shuchkin\SimpleXLSXGen;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Listar todos los autores (findAll)
    public function index()
    {
        return response()->json(Author::all(), 200);
    }

    // Crear un autor (create)
    public function store(Request $request)
    {
        // Se valida si ya existe
        if (Author::where('name', $request->name)->first()) {
            return response()->json(['error' => 'Author already exists'], 400);
        }

        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $author = Author::create($data);
        return response()->json($author, 201);
    }

    // Mostrar un autor específico (findOne)
    public function show(Author $author)
    {
        return response()->json($author, 200);
    }

    // Actualizar autor (update)
    public function update(Request $request, Author $author)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $author->update($data);
        return response()->json($author, 200);
    }

    // Eliminar usuario (delete)
    public function destroy(Author $author)
    {
        $author->delete();
        return response()->json(['message' => 'Author deleted'], 204);
    }

    // Export archivo excel utilizando shuchkin/simplexlsxgen
    public function export()
    {
        // Se prepara la consulta
        $authors = Author::withCount('books')->get();

        $data = [];
        // Se crea la fila de encabezados.
        $data[] = ['ID', 'Nombre', 'Cantidad de Libros'];

        // Se agrega las filas
        foreach ($authors as $author) {
            $data[] = [$author->id, $author->name, $author->books_count];
        }

        // Se contruye el nombre del archivo
        $fileName = 'authors_' . time() . '.xlsx';
        $path = 'exports/' . $fileName;

        // Se genera el contenido del archivo XLSX
        $xlsx = SimpleXLSXGen::fromArray($data, 'Autores');

        // Guarda el archivo en el disco 'local' (storage/app)
        Storage::disk('local')->put($path, $xlsx);

        // Return file
        return response()->json(['message' => 'Archivo guardado exitosamente', 'path' => $path]);
    }
}
