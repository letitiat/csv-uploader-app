<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\FileController;

class PeopleTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_getPeopleCount()
    {
        $fileController = new FileController();
        $result = $fileController->getPeople("Mr & Ms Smith");


        $this->assertTrue(count($result) == 2);
    }
    
    public function test_getPeopleNamesAmpersand()
    {
        $fileController = new FileController();
        $result = $fileController->getPeople("Mr & Mrs A Smith");

        $person1 = $result[0]->getFullName();
        $person2 = $result[1]->getFullName();

        $this->assertTrue($person1 === "Mr Smith");
        $this->assertTrue($person2 === "Mrs A Smith");
    }
    
    public function test_getPeopleNamesAnd()
    {
        $fileController = new FileController();
        $result = $fileController->getPeople("Mr Waylon Smithers and Ms A Smith");

        $person1 = $result[0]->getFullName();
        $person2 = $result[1]->getFullName();

        $this->assertTrue($person1 === "Mr Waylon Smithers");
        $this->assertTrue($person2 === "Ms A Smith");
    }
    
    public function test_getPeopleNameFullStop()
    {
        $fileController = new FileController();
        $result = $fileController->getPeople("Mr F. Jones");

        $person = $result[0]->getFullName();
        
        $this->assertTrue($person === "Mr F. Jones");
    }
}