<?php
require_once "templates/header.php";

if (!playersRegistered()) {
    header("location: index.php");
}

if (isset($_POST['cell'])) {
    $win = play($_POST['cell']);

    if ($win) {
        header("location: result.php?player=" . getTurn());
    }
}

if (playsCount() >= 25) { // Update the limit to 25 for a 5x5 grid
    header("location: result.php");
}
?>

<h2><?php echo currentPlayer() ?>'s turn</h2>

<form method="post" action="play.php">

    <table class="tic-tac-toe" cellpadding="0" cellspacing="0">
        <tbody>

        <?php
        $lastRow = 0;
        for ($i = 1; $i <= 25; $i++) { // Change the loop limit to 25 for a 5x5 grid
            $row = ceil($i / 5); // Change the calculation for rows

            if ($row !== $lastRow) {
                $lastRow = $row;

                if ($i > 1) {
                    echo "</tr>";
                }

                echo "<tr class='row-{$row}'>";
            }

            $additionalClass = '';

            if ($i % 5 == 2 || $i % 5 == 4) { // Adjust border
                $additionalClass = 'vertical-border';
            } elseif ($i % 5 == 1 || $i % 5 == 3) { // Adjust border conditions for a 5x5 grid
                $additionalClass = 'horizontal-border';
            } elseif ($i % 5 == 0) { // Adjust border conditions for a 5x5 grid
                $additionalClass = 'center-border';
            }
            ?>

            <td class="cell-<?= $i ?> <?= $additionalClass ?>">
                <?php if (getCell($i) === 'x'): ?>
                    X
                <?php elseif (getCell($i) === 'o'): ?>
                    O
                <?php else: ?>
                    <input type="radio" name="cell" value="<?= $i ?>" onclick="enableButton()"/>
                <?php endif; ?>
            </td>

        <?php } ?>

        </tr>
        </tbody>
    </table>

    <button type="submit" disabled id="play-btn">Play</button>

</form>

<script type="text/javascript">
    function enableButton() {
        document.getElementById('play-btn').disabled = false;
    }
</script>

<?php
require_once "templates/footer.php";
?>
