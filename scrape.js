var page = require('webpage').create(),
    system = require('system'),
    loadInProgress = false,
    testindex = 0,
    debug = false,
    reg_no = system.args[1],
    pac_no = system.args[2];

page.onConsoleMessage = function (msg) {
    if (debug) {
        console.log(msg);
    }
};

page.onLoadStarted = function() {
    loadInProgress = true;
    if (debug) {
        console.log("load started");
    }
};

page.onLoadFinished = function() {
    loadInProgress = false;
    if (debug) {
        console.log("load finished");
    }
};

var steps = [
    function() {
        page.open("https://onlinebanking.aib.ie/inet/roi/login.htm");
    },
    function() {
        page.evaluate(function(reg_no) {
            document.querySelector('#regNumber_id').value = reg_no;
        }, reg_no);
        page.evaluate(function() {
            var button = document.querySelector('#nextButton');
            button.click();
        });
    },
    function() {
        page.evaluate(function(pac_no) {
            var pac_str = String(pac_no);
            for (var i = 1; i <= 3; i++) {
                var html = document.querySelector('label[for=digit'+i+'] strong').innerHTML;
                var num = html.replace( /^\D+/g, '');
                document.querySelector('#digit'+i).value = pac_str.charAt(num-1);
            }
        }, pac_no);
        page.evaluate(function() {
            var button = document.querySelector('#nextButton');
            button.click();
        });
    },
    function() {
        console.log(page.content);
    }
];

interval = setInterval(function() {
    if (!loadInProgress && typeof steps[testindex] == "function") {
        if (debug) {
            console.log("step " + (testindex + 1));
        }
        var r = steps[testindex]();
        testindex++;
    }
    if (typeof steps[testindex] != "function") {
        phantom.exit();
    }
}, 1500);
