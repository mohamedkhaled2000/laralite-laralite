<?php

namespace Laralite\Database;

use Laralite\File\File;
use Laralite\Http\Request;
use Laralite\Url\Url;
use PDO;

class Database {

    private function __construct()
    {
        
    }

    /**
     * 
     * Insetant database
     */

    protected static $instance;

    /**
     * 
     * Database connection
     */

    protected static $connection;

    /**
     * Table name
     */
    protected static $table;

    /**
     * Setter Query
     */
    protected static $setter;

    /**
     * Where Query
     */
    protected static $where;

    /**
     * join Query
     */
    protected static $join;

    /**
     * offset Query
     */
    protected static $offset;

    /**
     * group_by Query
     */
    protected static $group_by;

    /**
     * having Query
     */
    protected static $having;

    /**
     * select Query
     */
    protected static $select;

    /**
     * order_by Query
     */
    protected static $order_by;

    /**
     * limit Query
     */
    protected static $limit;

    /**
     * where_binding Query
     */
    protected static $where_binding = [];

    /**
     * binding Query
     */
    protected static $binding;
    
    /**
     * query Query
     */
    protected static $query;

    /**
     * having_binding Query
     */
    protected static $having_binding = [];

    /**
     * 
     * connect to database
     */

    private static function connect() {
        if (! static::$connection) {
           $database_data = File::require_file('config/database.php');
           extract($database_data);
           $dsn = 'mysql:dbname=' . $database . ';host=' . $host . '';
           $option = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_PERSISTENT => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "set NAMES " . $charset . " COLLATE " . $collation
           ];
           try {
            static::$connection = new PDO($dsn,$username,$password,$option);
           } catch (\PDOException $th) {
            throw new \Exception($th->getMessage());
           }
        }
    }


    /**
     * 
     * set instance 
     */

    private static function instance() {
        static::connect();

        if(! static::$instance) {
            static::$instance = new Database();
        }

        return static::$instance;
    }

    /**
     * 
     * Query function
     */

    public static function query($query = null) {
        static::instance();

        if($query == null) {
            if(! static::$table) {
                throw new \Exception('Table Not Found');
            }

            $query = "SELECT ";
            $query .= static::$select ?: '*';
            $query .= " FROM " . static::$table . " ";
            $query .= static::$join . " ";
            $query .= static::$where . " ";
            $query .= static::$group_by . " ";
            $query .= static::$having . " ";
            $query .= static::$order_by . " ";
            $query .= static::$limit . " ";
            $query .= static::$offset . " ";
        }

        static::$query = $query;
        static::$binding = array_merge(static::$where_binding,static::$having_binding);

        return static::instance();
    }

    /**
     * 
     * Select function
     */

    public static function select() {
        $select = func_get_args();
        $select = implode(',',$select);
        static::$select = $select;

        return static::instance();
    }

    /**
     * 
     * Join function
     * 
     * @param string $table
     * @param string $first
     * @param string $operation
     * @param string $second
     * @param string $type
     * 
     * @return Object $type
     */

    public static function join($table,$first,$operation,$second,$type = 'INNER') {
        static::$join .= " " . $type . " JOIN " . $table . " ON " . $first . $operation . $second . " ";

        return static::instance();
    }

    /**
     * 
     * Join function
     * 
     * @param string $table
     * @param string $first
     * @param string $operation
     * @param string $second
     * 
     * @return Object $type
     */

    public static function rightJoin($table,$first,$operation,$second) {
        static::join($table,$first,$operation,$second,'RIGHT');

        return static::instance();
    }

    /**
     * 
     * Join function
     * 
     * @param string $table
     * @param string $first
     * @param string $operation
     * @param string $second
     * 
     * @return Object $type
     */

    public static function leftJoin($table,$first,$operation,$second) {
        static::join($table,$first,$operation,$second,'LEFT');

        return static::instance();
    }
    
    /**
     * 
     * where function
     * 
     * @param string $colum
     * @param string $operation
     * @param string $value
     * @param string $type
     * 
     * @return Object $type
     */

    public static function where($colum,$operation,$value,$type = null) {
        $where = '`' . $colum . '`' . $operation . ' ? ';
        if (! static::$where) {
            $statment = " WHERE " . $where;
        } else {
            if ($type == null) {
                $statment = " AND " . $where;
            }else {
                $statment = " " . $type . " " . $where;
            }
        }

        static::$where .= $statment;
        static::$where_binding[] = htmlspecialchars($value);

        return static::instance();
    }

    /**
     * 
     * orWhere function
     * 
     * @param string $colum
     * @param string $operation
     * @param string $value
     * 
     * @return Object $type
     */

    public static function orWhere($colum,$operation,$value){
        static::where($colum,$operation,$value,'OR');
        return static::instance();
    }

    /**
     * 
     * groupBy function
     * 
     * 
     * @return Object $type
     */

     public static function groupBy(){
        $group_by = func_get_args();
        $group_by = "GROUP BY " . implode(',',$group_by) . " ";
        static::$group_by = $group_by;
        return static::instance();
    }
        
    /**
     * 
     * having function
     * 
     * @param string $colum
     * @param string $operation
     * @param string $value

     * 
     * @return Object $type
     */

    public static function having($colum,$operation,$value) {
        $having = '`' . $colum . '`' . $operation . ' ? ';
        if (! static::$having) {
            $statment = " HAVING " . $having;
        } else {
            $statment = " AND " . $having;
        }

        static::$having .= $statment;
        static::$having_binding[] = htmlspecialchars($value);

        return static::instance();
    }
        
    /**
     * 
     * having function
     * 
     * @param string $colum
     * @param string $type
     * 
     * @return Object $type
     */

    public static function orderBy($colum,$type = null) {
        $sep = static::$order_by ? " , " : " ORDER BY ";
        $type = $type == null ? null : strtoupper($type);
        $type = ($type != null && in_array($type,['ASC','DESC'])) ? $type : 'ASC';
        $statment = $sep . $colum . " " . $type . " ";

        static::$order_by .= $statment;

        return static::instance();
    }
        
    /**
     * 
     * limit function
     * 
     * @param string $limit
     * 
     * @return Object $type
     */

    public static function limit($limit) {
        static::$limit = "LIMIT " . $limit . " ";

        return static::instance();
    }

    /**
     * 
     * offset function
     * 
     * @param string $offset
     * 
     * @return Object $type
     */

    public static function offset($offset) {
        static::$offset = "OFFSET " . $offset . " ";

        return static::instance();
    }

    /**
     * 
     * Fetch Execute
     */

    private static function fetchExcute() {
        static::query(static::$query);
        $query = trim(static::$query,' ');
        $data = static::$connection->prepare($query);
        $data->execute(static::$binding);
        static::clear();

        return $data;
    }

    /**
     * 
     * Table name 
     */

    public static function table($table) {
        static::$table = $table;
        return static::instance();
    }

    /**
     * 
     * Get data 
     */

    public static function get() {
        $data = static::fetchExcute();
        $result = $data->fetchAll();

        return $result;
    }

    /**
     * 
     * First data 
     */

     public static function first() {
        $data = static::fetchExcute();
        $result = $data->fetch();

        return $result;
    }

    /**
     * 
     * Execute data
     * 
     * @param array $data
     * @param string $query
     * @param bool $where
     * 
     * @return void
     */

    private static function execute(array $data,$query,$where = null) {
        static::instance();

        if(! static::$table) {
            throw new \Exception('Table Not Found');
        }

        foreach ($data as $key => $value) {
            static::$setter .= '`' . $key . '` = ?, ';
            static::$binding[] = filter_var($value,FILTER_UNSAFE_RAW );
        }
        
        static::$setter = trim((string) static::$setter,', ');
        $query .= static::$setter;
        $query .= $where != null ? static::$where . " " : "";
        static::$binding = $where != null ? array_merge((array)static::$binding,static::$where_binding) : static::$binding;
        
        $data = static::$connection->prepare($query);
        $data->execute(static::$binding);

        static::clear();

    }

    /**
     * Insert function
     */

    public static function insert($data) {
        $table = static::$table;
        $query = "INSERT INTO " . $table . " SET " ;
        static::execute($data,$query);

        $object_id = static::$connection->lastInsertId();
        $object = static::table($table)->where('id','=',$object_id)->first();

        return $object_id;
    }

    /**
     * update function
     */

    public static function update($data) {
        $table = static::$table;
        $query = "UPDATE " . $table . " SET " ;
        static::execute($data,$query,true);

        return true;
    }

    /**
     * delete function
     */

    public static function delete() {
        $table = static::$table;
        $query = "DELETE FROM " . $table . " " ;
        static::execute([],$query,true);

        return true;
    }

    /**
     * paginate function
     */

    public static function paginate($item_per_page = 15) {
        static::query(static::$query);
        $query = trim(static::$query,' ');
        $data = static::$connection->prepare($query);
        $data->execute();
        $pages = ceil($data->rowCount() / $item_per_page);
        $page = Request::get('page');

        $current_page = (! is_numeric($page) || Request::get('page') < 1) ? '1' : $page;
        $offset = ($current_page - 1) * $item_per_page;

        static::limit($item_per_page);
        static::offset($offset);
        static::query();

        $data = static::fetchExcute();
        $result = $data->fetchAll();

        $response = ['data' => $result,'item_per_page' => $item_per_page,'current_page' => $current_page,'pages' => $pages];

        return $response;
    }


    /**
     * links function
     */

    public static function links($current_page , $pages) {
        $links = '';
        $from = $current_page - 2;
        $to = $current_page + 2;
        if ($from < 2) {
            $from = 2;
            $to = $from + 4;
        }

        if($to >= $pages) {
            $diff = $to - $pages + 1;
            $from = ($from > 2) ? $from - $diff : 2;
            $to = $pages - 1;
        }

        if($from < 2) {
            $from = 1;
        }

        if($to >= $pages) {
            $to = ($pages - 1);
        }

        if($pages > 1) {
            $links .= '<ul class="pagination">';
            $full_link = Url::path(Request::full_url());
            $full_link = preg_replace('/\?page=(.*)/','',$full_link);
            $full_link = preg_replace('/\&page=(.*)/','',$full_link);
            $current_page_active = $current_page == 1 ? 'active' : '';
            $href = strpos($full_link,'?') ? ($full_link.'&page=1') : ($full_link.'?page=1');
            $links .= "<li class='page-item $current_page_active'> <a class='page-link' href='$href' ><span aria-hidden='true'>&laquo;</span></a> </li>";

            for ($i = $from ; $i <= $to ; $i++ ) {
                $current_page_active = $current_page == $i ? 'active' : '';
                $href = strpos($full_link,'?') ? ($full_link.'&page='.$i) : ($full_link.'?page='.$i);
                $links .= "<li class='page-item $current_page_active'> <a class='page-link' href='$href' >$i</a> </li>";
            }

            if($pages > 1) {
                $current_page_active = $current_page == $pages ? 'active' : '';
                $href = strpos($full_link,'?') ? ($full_link.'&page='.$pages) : ($full_link.'?page='.$pages);
                $links .= "<li class='page-item $current_page_active'> <a class='page-link' href='$href' ><span aria-hidden='true'>&laquo;</span></a> </li>";
            }

            $links .= '</ul>';
        }

        return $links;
    }

    /**
     * Clear query
     */

    private static function clear() {
        static::$where = '';
        static::$join = '';
        static::$offset = '';
        static::$group_by = '';
        static::$having = '';
        static::$select = '';
        static::$order_by = '';
        static::$limit = '';
        static::$where_binding = [];
        static::$binding = '';
        static::$query = '';
        static::$instance = '';
        static::$having_binding = [];
    }

}