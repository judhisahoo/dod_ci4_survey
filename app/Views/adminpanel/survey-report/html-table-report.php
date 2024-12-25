<html>

<body style="background-color: black; color: white; font-family: Arial, sans-serif; font-size: 14px;">
    <table style="width:100%; border:1px solid white; border-collapse: collapse; color: white;">
        <thead>
            <tr>
                <th style="border: 1px solid white; padding: 5px; font-weight:bold;">Sl No</th>
                <th style="border: 1px solid white; padding: 5px; font-weight:bold;">Unit Title</th>
                <th style="border: 1px solid white; padding: 5px; font-weight:bold;">Demand By Emloyer</th>
                <th style="border: 1px solid white; padding: 5px; font-weight:bold;">Supplied By institution</th>
                <th style="border: 1px solid white; padding: 5px; font-weight:bold;">Indivisual Skill Scores</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $survey): ?>
                <tr>
                    <td style="border: 1px solid white; padding: 5px;"><?= $pageSlNo ?></td>
                    <td style="border: 1px solid white; padding: 5px;"><?= $survey['name'] ?></td>
                    <td style="border: 1px solid white; padding: 5px;"><?php echo (array_key_exists('employer_ratting', $survey)) ? $survey['employer_ratting'] : '0'; ?></td>
                    <td style="border: 1px solid white; padding: 5px;"><?php echo (array_key_exists('institution_ratting', $survey)) ? $survey['institution_ratting'] : '0'; ?></td>
                    <td style="border: 1px solid white; padding: 5px;"><?= $survey['skill_score'] ?></td>
                </tr>
                <?php $pageSlNo++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>