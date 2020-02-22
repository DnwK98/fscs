<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          crossorigin="anonymous">
    {{--    <link rel="stylesheet" type="text/css"--}}
    {{--          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css"/>--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/bootstrap-notify.css"/>

    <title>Fscs executor - Frontend</title>
</head>

<body style="min-height: 100vh; position: relative">
<header>
    <div class="bg-dark collapse" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-md-6 py-4">
                    <h4 class="text-white"></h4>
                    <p class="text-muted">--</p>
                </div>
                <div class="col-md-6 py-4 text-right">
                    <a class="btn btn-secondary" href="#" onclick="logoutController()">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
            <a href="#" onclick="serverListController()" class="navbar-brand d-flex align-items-center">
                <strong>FSCS executor</strong>
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarHeader"
                    aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</header>

<main role="main">

    <section class="text-center">
        <div class="container">
            <div id="router-container"></div>
        </div>
        <div class="jumbotron text-muted" style="margin-bottom: 0; margin-top: 5vh; opacity: 0">FSCS executor</div>
    </section>

</main>

<footer class="jumbotron text-muted" style="margin: 0; position: absolute; z-index:100; bottom: 0; left: 0; right: 0">
    <div class="container">
        <p class="float-right">
            <a href="/frontend/#">Back to top</a>
        </p>
        <p>
            FSCS executor ©
            <script>document.write(new Date().getFullYear());</script>
        </p>
    </div>
</footer>

<div id="vendor" style="display: none">
    <div id="login-view">
        <div class="row" style="padding-top: 20vmin;">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-left">
                <label for="login-token">Token</label>
                <input type="password" id='login-token' class="form-control">
                <span class="alert-danger"> [[error]] </span>
                <br/><br/>
                <div
                    class="form-control btn btn-secondary"
                    onclick="loginSubmitController($('#login-token').val())"
                >
                    Login
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
    <div id="loading-view">
        <div class="text-center" style="padding-top: 25vmin;">
            <div class="spinner-border" style="width: 4rem; height: 4rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div id="server-details-view">
        <div class="row" style="padding-top: 5vmin;">
            <div class="col-xs-12 text-left">
                Details...<br/><br/>
                <pre>[[data]]</pre>
            </div>
        </div>
    </div>
    <div id="server-list-view">
        <div class="row" style="padding-top: 5vmin;">
            <div onclick="serverCreateController()" class="btn btn-success form-control">
                Create Server
            </div>
        </div>
        <div class="row" style="padding-top: 5vmin;">
            <div class="col-xs-12 text-left" style="width: 100%; overflow: auto">
                <table id="[[serverDataTableId]]" style="width: 100%; font-size: 14px;"
                       class="table table-striped table-sm table-hover">
                </table>
            </div>
        </div>
    </div>
    <div id="server-create-view">
        <div class="row text-left" style="padding-top: 5vmin;">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <label for="port">Port</label>
                <input id="port" autocomplete="false" value="27015"
                       class="server-form-[[serverFormId]] form-control input-sm">

                <label for="port">Map</label>
                <select id="map" class="server-form-[[serverFormId]] form-control input-sm">
                    <option value="de_dust2">de_dust2</option>
                    <option value="de_mirage">de_mirage</option>
                </select>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <div class="row text-left" style="padding-top: 5vmin;">
            <div class="col-lg-6">
                <label for="team1-name">Team1 Name</label>
                <input id="team1-name" autocomplete="false" class="server-form-[[serverFormId]] form-control input-sm">

                <label for="team1-tag">Team1 Tag</label>
                <input id="team1-tag" autocomplete="false" class="server-form-[[serverFormId]] form-control input-sm">

                <div class="row">
                    <div class="col-6">
                        <div>
                            SteamId64
                            <input id="team1-player1-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team1-player2-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team1-player3-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team1-player4-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team1-player5-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                        </div>
                    </div>
                    <div class="col-6">
                        <div>
                            Name
                            <input id="team1-player1-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team1-player2-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team1-player3-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team1-player4-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team1-player5-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <label for="team2-name">Team2 Name</label>
                <input id="team2-name" autocomplete="false" class="server-form-[[serverFormId]] form-control input-sm">

                <label for="team2-tag">Team2 Tag</label>
                <input id="team2-tag" autocomplete="false" class="server-form-[[serverFormId]] form-control input-sm">

                <div class="row">
                    <div class="col-6">
                        <div>
                            SteamId64
                            <input id="team2-player1-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team2-player2-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team2-player3-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team2-player4-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team2-player5-steamid" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                        </div>
                    </div>
                    <div class="col-6">
                        <div>
                            Name
                            <input id="team2-player1-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team2-player2-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team2-player3-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team2-player4-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                            <input id="team2-player5-name" autocomplete="false"
                                   class="server-form-[[serverFormId]] form-control input-sm">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9"></div>
            <div class="col-lg-3" style="padding-top: 5vmin;">
                <div onclick="serverCreateSubmitController('server-form-[[serverFormId]]')"
                     class="btn btn-secondary form-control">Create
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.7/bootstrap-notify.js"></script>

<script>
    var store = {
        token: null,
        loading: false,
    };

    $(function () {
        loginController();
    });

    function loginController(error = "") {
        render('#loading-view');
        let token;
        if (token = Cookies.get('fscs-t')) {
            store['token'] = atob(token);
        }
        store.loading = true;
        $.ajax({
            type: "GET",
            url: '/api/v1/me',
            beforeSend: function (request) {
                request.setRequestHeader("Token", store.token);
            },
        })
            .fail(function (response) {
                store['token'] = null;
                store.loading = false;
                render('#login-view', {
                    error: error
                });
            })
            .done(function (response) {
                store.loading = false;
                serverListController();
            });
    }

    function loginSubmitController(loginToken) {
        render('#loading-view');
        Cookies.set('fscs-t', btoa(loginToken));
        store['token'] = loginToken;

        store.loading = true;
        $.ajax({
            type: "GET",
            url: '/api/v1/me',
            beforeSend: function (request) {
                request.setRequestHeader("Token", store.token);
            },
        })
            .fail(function (response) {
                store['token'] = null;
                store.loading = false;
                loginController("Wrong token");
            })
            .done(function (response) {
                notify('Successfully logged in', 'success');
                store.loading = false;
                serverListController();
            });
    }

    function logoutController() {
        store['token'] = null;
        Cookies.set('fscs-t', null);
        loginController();
    }

    function serverListController() {
        render('#loading-view');

        apiGet({url: '/api/v1/server'})
            .done(function (response) {
                let serverDataTableId = "server-data-table-" + Math.floor(Math.random() * 1000000) + 1;
                render('#server-list-view', {
                    serverDataTableId: serverDataTableId,
                });
                setTimeout(function () {
                    $('#' + serverDataTableId).DataTable({
                        data: response.data,
                        pageLength: 50,
                        order: [[0, "desc"]],
                        columns: [
                            {title: "Id", searchable: true, data: "id"},
                            {title: "Port", data: "port"},
                            {title: "Status", data: "status"},
                            {title: "Map", data: "map"},
                            {title: "Team1", data: "teams.0.name"},
                            {title: "Score1", data: "teams.0.score"},
                            {title: "Score2", data: "teams.1.score"},
                            {title: "Team2", data: "teams.1.name"},
                            {
                                title: "Options",
                                render: function (data, type, row, meta) {
                                    return '<div class="text-right" style="font-size: 10px; width: 100%;"> ' +
                                        '<div onClick="serverDetailsController(' + row.id + ')" title="info" class="btn btn-info" style="margin-right: 4px; padding: 0.2rem 0.7rem">i</div>' +
                                        '<div onClick="actionRestartServer(' + row.id + ')" title="restart" class="btn btn-warning" style="margin-right: 4px; padding: 0.2rem 0.7rem">r</div>' +
                                        '<div onClick="actionRemoveServer(' + row.id + ')" title="remove" class="btn btn-danger" style="padding: 0.2rem 0.6rem">x</div>' +
                                        '</div>';
                                }
                            }
                        ]
                    });
                }, 50);
            });
    }

    function serverDetailsController(id) {
        render('#loading-view');
        apiGet({
            url: '/api/v1/server/' + id
        })
            .done(function (response) {
                render('#server-details-view', {
                    data: JSON.stringify(response.data, null, 2)
                })
            });
    }

    function serverCreateController() {
        let serverFormId = Math.floor(Math.random() * 1000000) + 1;
        render('#server-create-view', {
            serverFormId: serverFormId
        });
    }

    function serverCreateSubmitController(formId) {
        let formClass = '.' + formId;
        console.log(formClass + '#port');
        apiPost({
            url: '/api/v1/server',
            data: JSON.stringify({
                port: parseInt($(formClass + '#port').val()),
                map: $(formClass + '#map').val(),
                teams: [
                    {
                        name: $(formClass + '#team1-name').val(),
                        tag: $(formClass + '#team1-tag').val(),
                        players: getPlayersFromServerForm(formClass, 1)
                    },
                    {
                        name: $(formClass + '#team2-name').val(),
                        tag: $(formClass + '#team2-tag').val(),
                        players: getPlayersFromServerForm(formClass, 2)
                    }
                ]
            })
        })
            .done(function (response) {
                notify('Server successfully created. Id: ' + response.data.id, 'success');
                serverDetailsController(response.data.id);
            });
    }

    function actionRestartServer(id) {
        apiPost({
            url: '/api/v1/server/' + id + '/restart'
        })
            .done(function (response) {
                notify('Restarted server ' + id, 'success');
            });
    }

    function actionRemoveServer(id) {
        notify('Not supported yet', 'warning')
    }

    function render(view, data = [], where = '#router-container') {
        $(where).fadeOut(1.5, function () {
            let html = $(view).html();
            for (const key in data) {
                html = html.split('[[' + key + ']]').join(data[key])
            }
            $(where).html(html);
            $(where).fadeIn(1.5);
            console.log(html);
        });
    }

    function apiGet(request) {
        if (store.loading) {
            // Allow only one loading
            return {
                done: function (callable) {
                }
            };
        }

        store.loading = true;
        return $.ajax({
            type: "GET",
            url: request.url,
            data: request.data,
            beforeSend: function (request) {
                request.setRequestHeader("Token", store.token);
            },
        })
            .fail(function (response) {
                console.error(response);
                store.loading = false;
                if (response.status === 401) {
                    store['token'] = null;
                    loginController("Invalid token");
                } else {
                    notify('<pre>' + JSON.stringify(response.responseJSON, null, 2) + '</pre>', 'danger');
                }
            })
            .done(function () {
                store.loading = false;
            });
    }

    function apiPost(request) {
        if (store.loading) {
            // Allow only one loading
            return new Promise(function (resolve, reject) {
                reject();
            });
        }

        store.loading = true;
        return $.ajax({
            type: request.type ? request.type : "POST",
            url: request.url,
            data: request.data,
            beforeSend: function (request) {
                request.setRequestHeader("Token", store.token);
            },
        })
            .fail(function (response) {
                console.error(response);
                store.loading = false;
                if (response.status === 401) {
                    console.log(response.status);
                    store['token'] = null;
                    loginController("Invalid token");
                } else {
                    notify('<pre>' + JSON.stringify(response.responseJSON, null, 2) + '</pre>', 'danger');
                }
            })
            .done(function () {
                store.loading = false;
            })
    }

    function notify(message, type = 'info') {
        $.notify(
            {
                message: message
            },
            {
                type: type,
                placement: {
                    from: "bottom",
                    align: "right"
                },
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            },
        );
    }

    function getPlayersFromServerForm(formClass, teamNumber) {
        players = [];
        for (let i = 1; i <= 5; i++) {
            if ($(formClass + '#team' + teamNumber + '-player' + i + '-steamid').val() !== '') {
                if ($(formClass + '#team' + teamNumber + '-player' + i + '-name').val() !== '') {
                    players.push({
                        steamId64: $(formClass + '#team' + teamNumber + '-player' + i + '-steamid').val(),
                        name: $(formClass + '#team' + teamNumber + '-player' + i + '-name').val(),
                    })
                }
            }
        }
        return players;
    }
</script>
