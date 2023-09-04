

<?php

session_start();
error_reporting(E_ERROR | E_PARSE);

function registerPlayers($playerX = "", $playerO = "") {
    $_SESSION['PLAYER_X_NAME'] = $playerX;
    $_SESSION['PLAYER_O_NAME'] = $playerO;
    setTurn('x');
    resetBoard();
    resetWins();
}

function resetBoard() {
    resetPlaysCount();

    for ($i = 1; $i <= 25; $i++) { // Change the loop limit to 25 for a 5x5 grid
        unset($_SESSION['CELL_' . $i]);
    }
}

function resetWins() {
    $_SESSION['PLAYER_X_WINS'] = 0;
    $_SESSION['PLAYER_O_WINS'] = 0;
}

function playsCount() {
    return $_SESSION['PLAYS'] ? $_SESSION['PLAYS'] : 0;
}

function addPlaysCount() {
    if (!$_SESSION['PLAYS']) {
        $_SESSION['PLAYS'] = 0;
    }

    $_SESSION['PLAYS']++;
}

function resetPlaysCount() {
    $_SESSION['PLAYS'] = 0;
}

function playerName($player = 'x') {
    return $_SESSION['PLAYER_' . strtoupper($player) . '_NAME'];
}

function playersRegistered() {
    return $_SESSION['PLAYER_X_NAME'] && $_SESSION['PLAYER_O_NAME'];
}

function setTurn($turn = 'x') {
    $_SESSION['TURN'] = $turn;
}

function getTurn() {
    return $_SESSION['TURN'] ? $_SESSION['TURN'] : 'x';
}

function markWin($player = 'x') {
    $_SESSION['PLAYER_' . strtoupper($player) . '_WINS']++;
}

function switchTurn() {
    switch (getTurn()) {
        case 'x':
            setTurn('o');
            break;
        default:
            setTurn('x');
            break;
    }
}

function currentPlayer() {
    return playerName(getTurn());
}

function play($cell = '') {
    if (getCell($cell)) {
        return false;
    }

    $_SESSION['CELL_' . $cell] = getTurn();
    addPlaysCount();
    $win = playerPlayWin($cell);

    if (!$win) {
        switchTurn();
    } else {
        markWin(getTurn());
        resetBoard();
    }

    return $win;
}

function getCell($cell = '') {
    return $_SESSION['CELL_' . $cell];
}

function playerPlayWin($cell = 1) {
    if (playsCount() < 5) { // Change the playsCount limit to 5 for a 5x5 grid
        return false;
    }

    $column = ($cell - 1) % 5; // Change the column calculation for a 5x5 grid
    $row = ceil($cell / 5); // Change the row calculation for a 5x5 grid

    $player = getTurn();

    return isVerticalWin($column, $player) || isHorizontalWin($row, $player) || isDiagonalWin($player);
}

function isVerticalWin($column = 1, $turn = 'x') {
    for ($i = 0; $i < 5; $i++) { // Change the loop limit to 5 for a 5x5 grid
        if (getCell($column + $i * 5) != $turn) { // Adjust cell index for a 5x5 grid
            return false;
        }
    }
    return true;
}

function isHorizontalWin($row = 1, $turn = 'x') {
    for ($i = 0; $i < 5; $i++) { // Change the loop limit to 5 for a 5x5 grid
        if (getCell($row * 5 + $i) != $turn) { // Adjust cell index for a 5x5 grid
            return false;
        }
    }
    return true;
}

function isDiagonalWin($turn = 'x') {
    $win = true;

    for ($i = 0; $i < 5; $i++) { // Change the loop limit to 5 for a 5x5 grid
        if (getCell($i * 6 + 1) != $turn) { // Adjust cell index for a 5x5 grid
            $win = false;
            break;
        }
    }

    if (!$win) {
        $win = true;
        for ($i = 0; $i < 5; $i++) { // Change the loop limit to 5 for a 5x5 grid
            if (getCell($i * 4 + 5) != $turn) { // Adjust cell index for a 5x5 grid
                $win = false;
                break;
            }
        }
    }

    return $win && getCell(13) == $turn; // Adjust cell index for a 5x5 grid
}

function score($player = 'x') {
    $score = $_SESSION['PLAYER_' . strtoupper($player) . '_WINS'];
    return $score ? $score : 0;
}

// Function to make a random move for the AI player
function makeRandomMove() {
    $availableCells = [];

    // Find all available empty cells
    for ($i = 1; $i <= 25; $i++) { // Change the loop limit to 25 for a 5x5 grid
        if (!getCell($i)) {
            $availableCells[] = $i;
        }
    }

    // Choose a random available cell and play it
    if (!empty($availableCells)) {
        $randomIndex = array_rand($availableCells);
        play($availableCells[$randomIndex]);
    }
}

// Example usage of the AI player
if (getTurn() == 'o') {
    makeRandomMove();
}

// You can continue using the play() function as before to handle player moves.

?>


