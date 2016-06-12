<?php

/**
 * Description of BaseSingletonGuacamole
 *
 * @author Alexis
 * @author Aurelie
 */
class BaseSingletonGuacamole {

    private static $instance;
    private $mysqli;
    private $statement;

    private function __construct()
    {
        $this->mysqli = null;
        $this->statement = null;
    }

    private static function connect()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new BaseSingletonGuacamole();
        }

        self::$instance->mysqli = new mysqli('web-server', 'root', '-+', 'guacamole_db'); //localhost correspond au nom du serveur de virt
    }

    private function disconnect()
    {
        self::$instance->mysqli->close();
        self::$instance = null;
    }

    /**
     * Data access function.
     * 
     * @param string $sql The SQL query.
     * @param mixed $params The parameters to replace (and their data types) in an array. Eg : array("i", $id);
     * @return mixed Result of the select statement. Data are formated in an array.
     */
    public static function select($sql, $params = null)
    {
        $data = array();
        self::connect();

        // S'il n'y a pas d'erreur de connection.
        if (!self::$instance->mysqli->connect_error)
        {
            try
            {
                // On prépare la requête.
                self::$instance->statement = self::$instance->mysqli->prepare($sql);
                if (self::$instance->statement === false)
                {
                    echo '<p>' . $sql . '</p>';
                    echo self::$instance->mysqli->error;
                }
                
                // Si la requête a des paramètres.
                if (!is_null($params))
                {
                    $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
                    $bindParamsMethod->invokeArgs(self::$instance->statement, $params);
                }

                // Récupération des résultats.
                self::$instance->statement->execute();
                $resultat = self::$instance->statement->get_result();

                if ($resultat === false)
                {
                    echo '<p>' . $sql . '</p>';
                    echo self::$instance->mysqli->error;
                }
            }
            catch (Exception $e)
            {
                // Handle exception.
                echo $e->getMessage();
            }
            finally
            {
                self::$instance->disconnect();
            }

            $data = self::fetchResult($resultat);
        }
        else
        {
            echo 'La connexion a échoué.';
            echo self::$instance->mysqli->connect_error;
        }

        return $data;
    }

    /**
     * Try to delete one or multiple rows according to the SQL query.
     * 
     * @param string $sql The SQL query.
     * @param mixed $params The parameters to replace (and their data types) in an array. Eg : array("i", $id);
     * @return bool True if the query has been executed. False if it failed.
     */
    public static function insertOrEdit($sql, $params = null)
    {
        $idInserted = false;

        self::connect();

        // S'il n'y a pas d'erreur de connection.
        if (!self::$instance->mysqli->connect_error)
        {
            try
            {
                echo self::$instance->mysqli->error;
                // On prépare la requête.
                self::$instance->statement = self::$instance->mysqli->prepare($sql);
                if (self::$instance->statement === false)
                {
                    echo '<p>' . $sql . '</p>';
                    echo self::$instance->mysqli->error;
                }

                // Si la requête a des paramètres.
                if (!is_null($params))
                {
//                    echo '<pre>';
//                    var_dump(self::$instance->statement);
//                    echo '</pre>';
                    $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
                    $bindParamsMethod->invokeArgs(self::$instance->statement, $params);
                }

                // Execution de la requête
                self::$instance->statement->execute();
                $idInserted = self::$instance->statement->insert_id;

                if ($idInserted === false)
                {
                    echo '<p>' . $sql . '</p>';
                    echo self::$instance->mysqli->error;
                }
            }
            catch (Exception $e)
            {
                // Handle exception.
                echo $e->getMessage();
            }
            finally
            {
                self::$instance->disconnect();
            }
        }
        else
        {
            echo 'La connexion a échoué.';
            echo self::$instance->mysqli->connect_error;
        }

        return (int) $idInserted;
    }

    /**
     * Try to delete one or multiple rows according to the SQL query.
     * 
     * @param string $sql The SQL query.
     * @param mixed $params The parameters to replace (and their data types) in an array. Eg : array("i", $id);
     * @return bool True if the query has been executed. False if it failed.
     */
    public static function delete($sql, $params = null)
    {
        $deleted = false;

        self::connect();

        // S'il n'y a pas d'erreur de connection.
        if (!self::$instance->mysqli->connect_error)
        {
            try
            {
                echo self::$instance->mysqli->error;
                // On prépare la requête.
                self::$instance->statement = self::$instance->mysqli->prepare($sql);

                // Si la requête a des paramètres.
                if (!is_null($params))
                {
                    $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
                    $bindParamsMethod->invokeArgs(self::$instance->statement, $params);
                }

                // Execution de la requête
                $deleted = self::$instance->statement->execute();

                if ($deleted === false)
                {
                    echo '<p>' . $sql . '</p>';
                    echo self::$instance->mysqli->error;
                }
            }
            catch (Exception $e)
            {
                // Handle exception.
                echo $e->getMessage();
            }
            finally
            {
                self::$instance->disconnect();
            }
        }
        else
        {
            echo 'La connexion a échoué.';
            echo self::$instance->mysqli->connect_error;
        }

        return $deleted;
    }

    /**
     * Utilitary function.
     * 
     * @param mixed $resultat Raw dataset from the mysql statement.
     * @return mixed Formated dataset.
     */
    private static function fetchResult($resultat)
    {
        $data = array();

        // S'il n'y a pas eu d'erreur lors de la requête.
        if (isset($resultat) && $resultat)
        {
            // On fetch les résultats.
            while ($row = $resultat->fetch_array())
            {
                $data[] = $row;
            }
        }

        return $data;
    }


}

