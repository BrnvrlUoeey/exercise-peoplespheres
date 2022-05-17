<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style type="text/css">
        dt {font-weight: bold; color: #000099;}
        dd {font-style: italic;}
        /**/
    </style>
    <script>
        const load = () => {
            const addInputBtn = document.getElementById('add-input');
            addInputBtn.onclick = function() {

                let lastInput = document.getElementById('last-input');
                let lastInputIndex = parseInt(lastInput.getAttribute('name').substring(5));
                let nextInputIndex = lastInputIndex + 1;
                let nextInputName = 'Input' + nextInputIndex;
                let nextInput = lastInput.cloneNode();

                lastInput.removeAttribute('id');
                nextInput.setAttribute('id', 'last-input');
                nextInput.setAttribute('name', nextInputName);

                let p = document.createElement('p');
                let nextLabel = document.createElement('label');
                let textLabel = document.createTextNode('Text #'+ nextInputIndex +' (dynamically added field)');
                nextLabel.setAttribute('for', nextInputName);
                nextLabel.appendChild(textLabel);
                p.appendChild(nextInput);
                p.appendChild(document.createTextNode (" "));
                p.appendChild(nextLabel);
                lastInput.parentNode.after(p);
            };
        }
        window.onload = load;
    </script>
</head>
<body>
    <form name="emailInput" method="GET" action="./email_processor.php">
        <fieldset>
            <legend>Base text entries for email composition processing</legend>
            <p>
                <input type="text" name="Input1" value="Jean-Louis" />
                <label for="Input1">Text #1 (ex: first name)</label>
            </p>
            <p>
                <input type="text" name="Input2" value="Jean-Charles Mignard" />
                <label for="Input2">Text #2 (ex: last name)</label>
            </p>
            <p>
                <input type="text" name="Input3" value="external" />
                <label for="Input3">Text #3 (ex: subdomain)</label>
            </p>
            <p>
                <input type="text" name="Input4" value="peoplespheres.fr" />
                <label for="Input4">Text #4 (ex: domain)</label>
            </p>
            <p>
                <input type="text" name="Input5" value="fr" />
                <label for="Input5">Text #5 (ex: tld)</label>
            </p>
            <p>
                <input type="text" name="Input6" id="last-input" value="" />
                <label for="Input6">Text #6 (additionnal field)</label>
            </p>
            <p>
                <input type="button" name="add-input" id="add-input" value="Add another text input" />
            </p>
        </fieldset>
        <fieldset>
            <legend>Pattern expression to drive interpretation of input texts.</legend>
            <textarea name="expression" cols="75" rows="4" />input1.eachWordFirstChars(1) ~ '.' ~ (input2.wordsCount() > 1 ? input2.lastWords(-1, false).eachWordFirstChars(1) ~ input2.lastWords(1) : input2) ~ '@' ~ input3 ~ '.' ~ input4 ~ '.' ~ input5</textarea>
            <label for="expression">Specify your pattern expression here</label>

            <p>There are some methods available on Input fields, which are treated as objects. Those can be chained.
                Input "objects" need to be qualified numerically to be identified. Available methods of inputs objects are :
                <dl>
                    <dt>wordsCount()</dt>
                    <dd>Return words count from processed value.</dd>

                    <dt>eachWordFirstChars(int $n)</dt>
                    <dd>Returns a string concatenating each word x first characters from processed value.</dd>

                    <dt>eachWordLastChars(int $n)</dt>
                    <dd>Returns a string concatenating each word x last characters from processed value.</dd>

                    <dt>firstWords(int $n)</dt>
                    <dd>Returns "first" x words from processed value.</dd>

                    <dt>lastWords(int $n)</dt>
                    <dd>Returns "last" x words from processed value.</dd>

                    <dt>substr(int $offset, ?int $length = null)</dt>
                    <dd>Keeps the portion of string specified by the offset and length parameters (php builtin function proxy).</dd>
                </dl>
            </p>
            <input type="submit" name="add-input" value="process form" />
            <p>
                <input type="checkbox" name="debug" value="debug" />
                <label for="debug">Debug mode (verbose output)</label>
            </p>
        </fieldset>
    </form>

    <?php
    ?>
</body>
</html>

