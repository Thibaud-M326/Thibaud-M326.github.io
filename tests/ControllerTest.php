<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once "./Controller/Controller.php";

class ControllerTest extends TestCase
{

    public function testImc()
    {

        $userInfo = [
            "Poids" => 70,
            "Taille" => 175
        ];
        $result = imc($userInfo);
        $this->assertEquals(22.86, round($result, 2));
    }

    public function testWhatPhysique()
    {
        $result = whatPhysique(22.9);
        $this->assertEquals("Corpulence normale", $result);
    }

    public function testDailyCaloriesTotal()
    {
        $meals = [
            ["Kcal" => 200],
            ["Kcal" => 500],
            ["Kcal" => 400],
            ["Kcal" => 800],
        ];
        $result = dailyCaloriesTotal($meals);
        $this->assertEquals(1900, $result);
    }

    public function testDailyCaloriesGoal()
    {
        $userInfo = [
            "Poids" => 70,
            "Activite" => "Sédentaire",
        ];
        $result = dailyCaloriesGoal($userInfo);
        $this->assertEquals(1904, $result);
    }

    public function testIsGoalAchieved()
    {
        $result = isGoalAchieved(1500, 1900);
        $this->assertEquals(true, $result);
    }
}
