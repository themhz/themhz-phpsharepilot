<?php
namespace SharePilotV2\Components;

$timezone = new TimeZone();


if(isset($_REQUEST["timezone"])){
    $timezone->SetCurrentTimeZone($_REQUEST["timezone"]);
}else{
    $timezone->SetCurrentTimeZone("UTC");
}


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

</script>


