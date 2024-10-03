<?php

namespace App\Services;

use App\Models\Note;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NoteService
{
    public $user;
    public $request;

    public function __construct(Request $request)
    {
        $this->user = auth()->user();
        $this->request = $request;
    }

    /* Crea una nota */
    public function create()
    {
        $this->request->validate(
            $this->rules(),
            $this->error_messages()
        );
        $data_request = $this->request->all();
        $user = $this->request->user();

        $note = Note::create([
            'title'         => $data_request['title'],
            'description'   => $data_request['description'],
            'user_id'       => $user->id,
            'tag'           => $data_request['tag'],
        ]);

        abort_if(!$note, 500, 'database failed');

        return $note;
    }

    /* Actualiza una nota */
    public function update(Note $note)
    {
        $this->is_owner($note);

        //verificar los datos
        $validated_data = $this->request->validate(
            $this->rules(true),
            $this->error_messages()
        );
        $result = $note->update($validated_data);

        abort_if(!$result, 500, 'database failed');

        return $note;
    }

    /* Elimina una nota */
    public function delete(Note $note) : void
    {
        $this->is_owner($note);

        $result = $note->delete();

        abort_if(!$result, 500, 'database failed');

        return;

    }

    public function getAll()
    {
        return $this->request->user()->notes;
    }
    /**
     * @param string $type asc = ascendente, desc = descendente
     * @return array notas */  

    public function getAll_ordered_by_date($type = 'asc')
    {
        abort_if($type !== 'asc' && $type !== 'desc', 400, 'unknown queryString');
        return $this->request->user()->notes()->orderBy('created_at', $type)->get()->toArray();
    }

    public function get_note($id)
    {
        $note = Note::find($id);
        $this->is_owner($note);

        return $note;
    }

    public function is_owner($note) : void
    {
        $is_owner = $this->request->user()->notes->contains($note);
        abort_if(!$is_owner, 401);

        return;
    }

    public function validate(array $data)
    {
        $result = Validator::make($data, $this->rules(), $this->error_messages());

        if ($result->fails()) {
           
            throw new ValidationException($result);
        }
        return true;       

    }
    
    public function error_messages()
    {
        return [
            'title.required'    => 'El titulo es obligatorio',
            'title.string'      => 'El titulo debe ser un cadena de texto',
            'description'       => 'La descripcion de obligatoria',
            'tag.required'      => 'El tag es obligatorio',
            'tag.string'        => 'El tag debe ser una cadena de texto'
        ];
    }

    public function rules($update = false)
    {
        if(!$update){
            return [
                'title'         => 'required|string|max:255',
                'description'   => 'required|string',
                'tag'           => 'required|string'
            ];
        }else{
            return [
                'title'         => 'sometimes|string|max:255',
                'description'   => 'sometimes|string',
                'tag'           => 'sometimes|string'
            ];
        }

    }

}