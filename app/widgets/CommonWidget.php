<?php

class CommonWidget
{
    public static function headerPanel()
    {
        $s =
            '<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w0-collapse">
                            <span class="sr-only">Переключить навигацию</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/">Список сообщений</a>
                    </div>
                    <div class="navbar-collapse collapse" aria-expanded="true" id="w0-collapse">' .
            (new UserWidget)->htmlLogoutItem(
                [
                    'form' => ['class' => 'navbar-right nav', 'role' => 'form'],#['class' => 'navbar-right'],
                    'button' => ['class' => 'btn navbar-btn', 'type' => 'submit']
                ]
            )
            . '      </div>
                </div>
            </nav>';

//        $s = '<nav class="navbar navbar-default">
//                  <div class="container-fluid">
//
//                    <div class="navbar-header">
//                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="true">
//                        <span class="sr-only">Toggle navigation</span>
//                        <span class="icon-bar"></span>
//                        <span class="icon-bar"></span>
//                        <span class="icon-bar"></span>
//                      </button>
//                      <a class="navbar-brand" href="#">Brand</a>
//                    </div>
//
//
//    <div class="navbar-collapse collapse in" id="bs-example-navbar-collapse-1" aria-expanded="true" style="">
//      <ul class="nav navbar-nav">
//        <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
//        <li><a href="#">Link</a></li>
//        <li class="dropdown">
//          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
//          <ul class="dropdown-menu" role="menu">
//            <li><a href="#">Action</a></li>
//            <li><a href="#">Another action</a></li>
//            <li><a href="#">Something else here</a></li>
//            <li class="divider"></li>
//            <li><a href="#">Separated link</a></li>
//            <li class="divider"></li>
//            <li><a href="#">One more separated link</a></li>
//          </ul>
//        </li>
//      </ul>
//      <form id="signin" class="navbar-form navbar-right" role="form">
//                        <div class="input-group">
//                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
//                            <input id="email" class="form-control" name="email" value="" placeholder="Email Address" type="email">
//                        </div>
//
//                        <div class="input-group">
//                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
//                            <input id="password" class="form-control" name="password" value="" placeholder="Password" type="password">
//                        </div>
//
//                        <button type="submit" class="btn btn-primary">Login</button>
//                   </form>
//
//    </div>
//  </div>
//</nav>';

        return $s;
    }
}