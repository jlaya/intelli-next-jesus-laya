<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Shuchkin\SimpleXLSXGen;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Listar todos los libros (findAll)
    public function index()
    {
        return response()->json(Book::all(), 200);
    }

    // Crear un libro (create)
    public function store(Request $request)
    {
        // Se valida si ya existe
        if (Book::where('title', $request->name)->first()) {
            return response()->json(['error' => 'Book already exists'], 400);
        }

        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            'author_id' => 'required|exists:authors,id',
        ]);

        $book = Book::create($data);
        return response()->json($book, 201);
    }

    // Mostrar un libro específico (findOne)
    public function show(Book $book)
    {
        return response()->json($book, 200);
    }

    // Actualizar libro (update)
    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            'author_id' => 'required|exists:authors,id',
        ]);

        $book->update($data);
        return response()->json($book, 200);
    }

    // Eliminar libro (delete)
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'Book deleted'], 204);
    }

    // Export archivo excel utilizando shuchkin/simplexlsxgen
    public function export()
    {
        $books = Book::with('author')->get();

        $data = [];
        $data[] = ['ID', 'Título', 'Categoría', 'Autor'];

        foreach ($books as $book) {
            $data[] = [
                $book->id,
                $book->title,
                $book->category,
                $book->author ? $book->author->name : 'N/A'
            ];
        }

        $fileName = 'books_' . time() . '.xlsx';
        $path = 'exports/' . $fileName;

        $xlsx = SimpleXLSXGen::fromArray($data, 'Libros');
        Storage::disk('local')->put($path, $xlsx);

        return response()->json(['message' => 'Archivo guardado exitosamente', 'path' => $path]);
    }
}
