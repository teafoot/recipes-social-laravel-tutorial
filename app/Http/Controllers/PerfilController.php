<?php

namespace App\Http\Controllers;

use App\Perfil;
use App\Receta;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $perfil)
    {

        // Obtener las recetas con paginación
        $recetas = Receta::where('user_id', $perfil->user_id)->paginate(10);
       
        //
        return view('perfiles.show', compact('perfil', 'recetas') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $perfil)
    {
        // Ejecutar el Policy
        $this->authorize('view', $perfil); // solo el mismo usuario autenticado puede ver su propio formulario de edicion de perfil


        //
        return view('perfiles.edit', compact('perfil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perfil $perfil)
    {
        // Ejecutar el Policy
        $this->authorize('update', $perfil);

        // Validar
        $data = request()->validate([
            'nombre' => 'required',
            'url' => 'required',
            'biografia' => 'required'
        ]);

        // Si el usuario sube una imagen
        if( $request['imagen'] ) {
            // obtener la ruta de la imagen
            $ruta_imagen = $request['imagen']->store('upload-perfiles', 'public');

            // Resize de la imagen
            $img = Image::make( public_path("storage/{$ruta_imagen}"))->fit(600, 600 );
            $img->save(); // overwrite image

            // Crear un arreglo de imagen
            $array_imagen = ['imagen' => $ruta_imagen];
        } 

        // Asignar nombre y URL
        auth()->user()->name = $data['nombre'];
        auth()->user()->url = $data['url'];
        auth()->user()->save();

        // Eliminar url y name de $data
        unset($data['nombre']);
        unset($data['url']);


        // Guardar información
        // Asignar Biografia e imagen
        auth()->user()->perfil()->update( array_merge(
            $data, // solo la biografia
            $array_imagen ?? []
        ) );


        // redireccionar
        return redirect()->action('RecetaController@index');
    }

}
