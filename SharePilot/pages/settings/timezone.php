<?php
namespace SharePilotV2\Components;

$timezone = new TimeZone();
$dbTimeZone = $timezone->GetTimeZoneFromDb();
$timezone->SetCurrentTimeZone($dbTimeZone["timezone"]);

echo "<br>";
echo '<select id="timezone-select" name="timezone">';
    foreach($timezone->GetTimezonesList() as $tz) {
        echo '<option value="' . $tz . '"' . ($tz === $timezone->GetCurrentTimeZone() ? ' selected' : '') . '>' . $tz . '</option>';
    }
    echo '</select>';

    echo '<script>';
    echo 'var defaultTimezone = "' . $timezone->GetCurrentTimeZone() . '";';
    echo '</script>';
?>

<p id="clock"></p>
<input type="button" value="save" onclick="saveTimeZone()">

<script>
    let serverTimezone = defaultTimezone;

    const displayServerTime = () => {
        const now = new Date();
        const options = { timeZone: serverTimezone, hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const formatter = new Intl.DateTimeFormat('en-US', options);
        document.getElementById('clock').textContent = formatter.format(now);
        setTimeout(displayServerTime, 1000);
    }

    displayServerTime();

    document.getElementById('timezone-select').addEventListener('change', function() {
        serverTimezone = this.value;
    });


    function saveTimeZone(){
            fetch(`settings?method=savetimezone&timezone=${serverTimezone}&format=raw`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({"timezone":serverTimezone}),
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
</script>






