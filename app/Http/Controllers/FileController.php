<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class FileController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fileUpload');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv|max:2048',
        ]);
    
        $fileName = time().'.'.$request->file->extension();  
     
        $request->file->move(public_path('uploads'), $fileName);

        $file = public_path('uploads') . "/" . $fileName;
        $lines = file($file); // read the file as an array of lines
        
        $people = array();
        foreach($lines as $i=>$line) {            
            // Skip header
            if  ($i == 0) {
                continue;
            }

            // Get the first cell
            $name = explode(',', $line)[0];

            $people = array_merge($people, $this->getPeople($name));
        }

        return view("welcome")
            ->with('people', $people);
    }

    public function getPeople(string $fullName) {
        $result = array();

        $parts = explode(' ', $fullName);
        
        // Set a default value, and override when either 'and' or '&' occurs
        $names = array($fullName);

        if(in_array("&", $parts)) {
            $names = explode("&", $fullName);
        }

        if(in_array("and", $parts)) {
            $names = explode("and", $fullName);
        }

        foreach ($names as $name) {
            $person = $this->getPerson($name, $parts);            
            array_push($result, $person);
        }

        return $result;
    }

    private function getPerson(string $name, array $fullNameParts) {
        // explode - splits the name
        // filter - removes the empties
        // values - gets just the values
        $parts = array_values(array_filter(explode(' ', $name), function($value) { return $value !== ''; }));

        $length = count($parts);

        $person = new Person();
        $person->title = $parts[0];

        switch ($length) 
        {
            case 1: // Mr - has and in full name
                $person->last_name = end($fullNameParts);
                break;
            case 2: // Mr Smith 
                $person->last_name = $parts[1];                
                break;
            case 3: // Mr Alan Smith / Mr A. Smith
                $value = str_replace('.', '', $parts[1]);
                if(strlen($value) == 1) {
                    $person->initial = $parts[1];
                } else {
                    $person->first_name = $parts[1];
                }
                $person->last_name = $parts[2];  

                break;
        }
        
        return $person;
    }
}
