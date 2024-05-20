<div class="placeholder">
    <br />
</div>
<footer id="footer" class="fixed-bottom navbar navbar-primary bg-primary">
    <div class="col-12 px-0 text-center">
        <ul class="list-inline my-0 p02">
            <li class="list-inline-item mr-2">
                <small>
                    <i class="fa-brands fa-paypal"></i>
                    <a href="https://paypal.me/rregenmantel" target="_blank">
                        <font class="footertext">Spenden</font>
                    </a>
                </small>
            </li>
            <li class="list-inline-item mr-2">
                <small>
                    <i class="fa-brands fa-discord"></i>
                    <a href="https://discord.gg/73uYTK2nmp" target="_blank">
                        <font class="footertext">Discord Server</font>
                    </a>
                </small>
            </li>
            <li class="list-inline-item mr-2">
                <small>
                    <i class="fa-solid fa-comment"></i>
                    <a href="/improvement">
                        <font class="footertext">Feedback</font>
                    </a>
                </small>
            </li>
            <li class="list-inline-item mr-2">
                <small>
                    <font>
                        <i class="fa-solid fa-spinner"></i> Ladezeit: <?php echo round((microtime(true) - $time) * 1000, 2); ?> ms
                    </font>
                </small>
            </li>
            <li class="list-inline-item mr-2">
                <small>
                    <font>
                        <i class="fa-solid fa-database"></i> Version: <?php echo $World_User->getVersion(); ?>
                    </font>
                </small>
            </li>
            <li class="list-inline-item mr-2">
                <small>
                    <select class="" id="changeWorld">
                    </select>
                </small>
            </li>
        </ul>
    </div>
</footer>
<script>
    const select = $('#changeWorld');
    const lastWorld = localStorage.getItem("LastWorld");

    $.ajax({
        url: '/ajax/footer/getUserWorlds.php',
        type: 'POST',
        success: function(response) {
            const worlds = JSON.parse(response);
            select.empty();

            if (worlds.length > 0) {
                worlds.forEach(function(world) {
                    const isSelected = world === lastWorld;
                    select.append(new Option(world, world, isSelected, isSelected));
                });
            } else {
                select.append(new Option('Keine Aktiven Welten gefunden.', ''));
            }
        },
        error: function() {
            alert('Fehler beim Suchen der User-Welten.');
        }
    });

    $('#changeWorld').change(function() {
        const selectedWorld = $(this).val();

        $.ajax({
            url: '/ajax/footer/changeWorld.php',
            type: 'POST',
            data: {
                changeworld: selectedWorld
            },
            success: function(response) {
                localStorage.setItem("LastWorld", selectedWorld);
                window.location.reload();
            }
        });
    });
</script>