<?php
/* 
    Document   : index.php
    Created on : 6/25/2018
    Author     : Bryan Elliott (melliatto@yahoo.com)
    Description: Spin Viewer main index page
*/

// stateList array for State select box below
$stateList = array('AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming');
?>
<!DOCTYPE html>
<head>
    <title>Auto Spinner</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <div id="spinner"><svg id="header-spinner" width="32px" height="32px" viewBox="0 0 128 128"><rect x="0" y="0" width="100%" height="100%" fill="transparent" /><g><path  fill="#0f75bc" fill-opacity="1" d="M109.25 55.5h-36l12-12a29.54 29.54 0 0 0-49.53 12H18.75A46.04 46.04 0 0 1 96.9 31.84l12.35-12.34v36zm-90.5 17h36l-12 12a29.54 29.54 0 0 0 49.53-12h16.97A46.04 46.04 0 0 1 31.1 96.16L18.74 108.5v-36z"/></g></svg></div>
            <h1 id="main-title">Spin Viewer</h1>
        </div>

        <div id="main">
            <div class="container">
                <p class="top-text">Paste your spun text in the textbox below and click "Spin It" to view a sample of the spun output.<br />To view another variation of spun output, click "Spin It" again.</p>
                <form action="#" method="POST" autocomplete="off">
                    <div class="options-wrapper">
                        <div class="bracket-container">
                            <label class="switch-title">Bracket Type? </label>
                            <div class="switch-field">
                                 
                                <input type="radio" id="switch-left" name="bracket" value="\[\[\[,\]\]\]" checked>
                                <label for="switch-left">[[[ ]]]</label>
                                <input type="radio" id="switch-right" name="bracket" value="\{,\}">
                                <label for="switch-right">{ }</label>

                            </div>
                        </div>
                        <div class="geo-container">
                            <div class="geo-switch">
                                <div class="geo-text">Specify CITY/STATE?</div> 
                                <label class="switch">
                                    <input id="geo-checkbox" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div id="slidedown-container" class="slideup">
                                <div>
                                    City: 
                                    <input class="city-input" id="city-input" name="city-input" autocomplete="nope" value="Birmingham">
                                </div>
                                <div>
                                    State: 
                                    <select id="state-input" name="state-input" autocomplete="nope">
                                        <?php
                                            foreach ($stateList as $key=>$val) {
                                                $selected = $val == "Alabama" ? ' selected="selected"' : ''; 
                                                echo '<option value="'.$val.'"'.$selected.'>'.$val.'</option>'."\n";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <textarea name="textbox" id="textbox" placeholder="Paste spin text(HTML) here." autocomplete="nope" onfocus="this.select();" ></textarea>
                    <div class="text-right">
                        <button type="submit" id="submit" name="submit">
                    
                        <svg id="btn-spinner" width="32px" height="32px" viewBox="0 0 128 128"><rect x="0" y="0" width="100%" height="100%" fill="transparent" /><g><path  fill="#ffffff" fill-opacity="1" d="M109.25 55.5h-36l12-12a29.54 29.54 0 0 0-49.53 12H18.75A46.04 46.04 0 0 1 96.9 31.84l12.35-12.34v36zm-90.5 17h36l-12 12a29.54 29.54 0 0 0 49.53-12h16.97A46.04 46.04 0 0 1 31.1 96.16L18.74 108.5v-36z"/></g></svg>
                            <div id="btn-text">Spin It!</div></button>
                    </div>
                </form>
                <div class="clear"></div>
                <div id="output"></div>
            </div> <!-- end .container -->
        </div> <!-- end #main -->

        <div id="footer">
            <div class="container">
                <p>&copy; <?php echo date("Y"); ?>, Spin Viewer Tool.</p>
            </div>
            
        </div>

    </div> <!-- end #wrapper -->
    <script>
        // 'Spin It' button
        const button = document.getElementById('submit');
        // output div
        const output = document.getElementById('output');
        // header spinner icon
        const headerSpinner = document.getElementById('header-spinner');
        // button spinner icon
        const btnSpinner = document.getElementById('btn-spinner');
        // Textbox input
        const textbox = document.getElementById('textbox');
        // 'Bracket type' radio buttons
        const brackets = document.getElementsByName('bracket');
        // GEO toggle button
        const toggleBtn = document.getElementById('geo-checkbox');
        // slidedown/slideup container
        const slideContainer = document.getElementById('slidedown-container');
        // city input
        const cityInput = document.getElementById('city-input');
        // state input
        const stateInput = document.getElementById('state-input');
        
        // Set default checked radio button for 'bracket type'
        if (localStorage.getItem('defaultBrackets') !== null) {
            checkedRadioIndex = localStorage.getItem('defaultBrackets');
            for (let j = 0; j < brackets.length; j++) {
                brackets[j].checked = (j == checkedRadioIndex ? true : false);
            }
        }

        // 'Spin It' button click event listener
        button.addEventListener('click', e => {
            e.preventDefault();

            // Spin the spinner icon
            const spinHeaderAttr = (headerSpinner.getAttribute('class') == 'spin' ? '' : 'spin');
            const spinBtnAttr = (btnSpinner.getAttribute('class') == 'spin' ? '' : 'spin');
            headerSpinner.setAttribute('class', spinHeaderAttr);
            btnSpinner.setAttribute('class', spinBtnAttr);

            // Get the spun content from the textbox
            const spinText = (textbox.value !== '' ? textbox.value : false);

            // Get the 'Bracket type' from radio buttons input.
            let bracket = false;
            for (let i = 0; i < brackets.length; i++) {
                if (brackets[i].checked) {
                    bracket = brackets[i].value;
                    // Save the selected bracket type to local storage
                    localStorage.setItem('defaultBrackets', i);
                }
            }

            // Get toggle button city/state values
            const city = (toggleBtn.checked && cityInput.value !== '' ? cityInput.value : false);
            const state = (toggleBtn.checked && stateInput.value !== '' ? stateInput.value : false);
            
            // Build querystring parameters
            let parameters = [];

            if (spinText) {
                parameters.push(`spin=${spinText}`);
            }

            if (bracket) {
                parameters.push(`bracket=${bracket}`);
            }

            if (city && state) {
                parameters.push(`city=${city}&state=${state}`);
            }

            const queryString = parameters.join('&');
            
            // Send AJAX request to spinnerAjax.php with queryString
            getSpinText(queryString);
            
        });

        toggleBtn.addEventListener('change', e => {
            if (toggleBtn.checked) {
                slideContainer.className = 'slidedown';
            } else {
                slideContainer.className = 'slideup';
            }
        });
        
        // Get spin Button position coordinates
        const buttonPosY = button.getBoundingClientRect().top;
        const buttonPosX = button.getBoundingClientRect().left - 8;
        button.style.left = buttonPosX + 'px';

        // Fix button position to upper right corner on scroll
        window.onscroll = function changeClass(){
            let scrollPosY = window.pageYOffset | document.body.scrollTop; 
            button.className = (scrollPosY > (buttonPosY - 15)) ? 'fixed' : 'static';
        }

        // Send Ajax request function (called on button click event listener)
        function getSpinText(queryString) {

            const xhr = new XMLHttpRequest();

            xhr.open('POST', 'spinnerAjax.php', true);

            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (this.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response) {
                        output.style.visibility = 'visible';
                        output.innerHTML = response; 
                    }
                }
            }

            xhr.send(queryString);
        }
    </script>
</body>
</html>
  