<?php

namespace Router;

use Source\App;
use Source\Dump;
use Source\Renderer;

class RouteController
{
    public function index()
    {
        $methods = App::$router->getRoutes();
        $namedRoutes = App::$router->getNamedRoutes();

        echo "<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM\" crossorigin=\"anonymous\"><style>body{background-color: #202020; padding-inline: 1.5rem; color: #FAFAFA} tr{border-bottom: 1px solid #666}</style>";

        foreach ($methods as $method => $routes) {
            echo " <h2>" . $method . " (" . count($routes) . ") </h2>";
            echo "<table class='table table-dark table-striped'>";

            echo "<th>No</th>";
            echo "<th>METHODE</th>";
            echo "<th>PATH</th>";
            echo "<th>CONTROLLER</th>";
            echo "<th>PARAMS</th>";
            echo "<th>MIDDLEWARE</th>";
            echo "<th>Name</th>";

            foreach ($routes as $key => $route) {
                $params = '';
                foreach ($route->getParams() as $value => $regex) {
                    $params .= $value . " ($regex), ";
                }
                $params = trim($params, ",");
                echo "<tr>";
                echo "<td>#" . $key + 1 . "</td>";
                echo "<td>$method</td>";
                echo "<td>" . $route->getPath() . "</td>";
                echo "<td>" . $route->getCallable()[0] . '@' . $route->getCallable()[1] . "</td>";
                echo "<td>" . $params . "</td>";
                echo "<td>" . $route->getMiddleware() . "</td>";
                echo "<td>";
                foreach ($namedRoutes as $name => $namedRoute) {
                    if ($namedRoute === $route) {
                        echo $name;
                        $named = true;
                    }
                }
                if (!$named ?? false) echo "___";
                echo '</td>';






                echo "</tr>";
                // Dump::dd($namedRoutes);
            }
            echo "</table>";
        }


        // Dump::dd($router);
    }
}
