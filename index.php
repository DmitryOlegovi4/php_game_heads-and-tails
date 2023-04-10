<h1>Игра "Орёл и решка"</h1>
<form method="post">
    <label>Player1 <input type="text" name="player1" placeholder="Кол-во очков" /></label>
    <br>
    <label>Player2 <input type="text" name="player2" placeholder="Кол-во очков" /></label>
    <br>
    <input type="submit" value="Рассчитать" />
</form>

<?php

class Player{
    public $name;
    public $coins;
    public function __construct($name, $coins)
    {
        $this -> name = $name;
        $this -> coins = $coins;
    }
    public function point(Player $player)
    {
        $this -> coins++;
        $player -> coins--;
    }
    public function calcChance(Player $player)
    {
        return round($this -> coins / ($this -> coins + $player -> coins),3) * 100 . "%";
    }
    public function bankrupt()
    {
        return $this -> coins == 0;
    }
}
class Game{
    protected $player1;
    protected $player2;
    protected $flips;
    public function __construct(Player $player1,Player $player2)
    {
        $this -> player1 = $player1;
        $this -> player2 = $player2;
    }

    public function flip()
    {
        return rand(0,1) ? "орёл" : "решка";
    }
    public function start()
    {

        echo <<<EOT
            Кол-во очков в начале: {$this -> player1 -> name} - {$this -> player1 -> coins} , {$this -> player2 -> name} - {$this -> player2 -> coins}; <br/>
            Шанс на выйгрыш <br/>
            у {$this -> player1 -> name} : {$this -> player1 -> calcChance($this -> player2)}; <br/>
            у {$this -> player2 -> name} : {$this -> player2 -> calcChance($this -> player1)}; <br/>
            <br/>
        EOT;

        $this -> play();
    }
    public function play()
    {
        while(true){
            if($this -> flip() == "орёл"){
                $this -> player1 -> point($this -> player2);
            }else{
                $this -> player2 -> point($this -> player1);
            }
            if($this -> player1 -> bankrupt() || $this -> player2 -> bankrupt()){
                return $this -> end();
            }
            $this -> flips++;
        }
    }
    public function winner(): Player
    {
        return $this -> player1 -> coins > $this -> player2 -> coins ? $this -> player1 : $this -> player2;
    }

    public function end()
    {
        echo <<<EOT
            Game over.<br/>
            {$this -> player1 -> name} : {$this -> player1 -> coins};<br/>
            {$this -> player2 -> name} : {$this -> player2 -> coins};<br/>
            Победитель: {$this -> winner() -> name};<br/>
            Кол-во подбрасываний: {$this -> flips};<br/>
        EOT;
    }
}
$coins_pl1 = 100;
$coins_pl2 = 100;
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $coins_pl1 = htmlspecialchars($_POST["player1"]);
    $coins_pl2 = htmlspecialchars($_POST["player2"]);
}

$game = new Game(
    new Player("Player1", $coins_pl1),
    new Player("Player2", $coins_pl2),
);

$game -> start();