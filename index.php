<form name="emailGenerator" method="GET" action="./email_processor.php">
    <fieldset>
        <legend>Base text entries for email composition processing</legend>
        <p>
            <label for="Input1">Text #1 (ex: first name)</label>
            <input type="text" name="Input1" value="" />
        </p>
        <p>
            <label for="Input2">Text #2 (ex: last name)</label>
            <input type="text" name="Input2" value="" />
        </p>
        <p>
            <label for="Input3">Text #3 (ex: subdomain)</label>
            <input type="text" name="Input3" value="" />
        </p>
        <p>
            <label for="Input4">Text #4 (ex: domain)</label>
            <input type="text" name="Input4" value="" />
        </p>
        <p>
            <label for="Input5">Text #5 (ex: tld)</label>
            <input type="text" name="Input5" value="" />
        </p>
        <p>
            <label for="Input6">Text #6 (additionnal field)</label>
            <input type="text" name="Input6" value="" />
        </p>
        <p>
            <label for="Input1">Add other text input</label>
            <input type="button" name="add-input" value="" />
        </p>
    </fieldset>
    <fieldset>
        <legend>Pattern expression to drive interpretation of input texts.</legend>
        <label for="expression">Text #6 (additionnal field)</label>
        <textarea name="expression" cols="70" rows="8" /></fieldset>
        <p>
            Available methods of inputs objects are :
            <ul>
                <li>wordsCount()</li>
                <li>eachWordFirstChars(int $n)</li>
                <li>eachWordLastChars(int $n)</li>
                <li>firstWords(int $n)</li>
                <li>lastWords(int $n)</li>
            </ul>
        </p>
    </fieldset>

</form>

<?php
