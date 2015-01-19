<?php

namespace Vreasy\Models;

use Vreasy\Query\Builder;

class Task extends Base
{
    // Protected attributes should match table columns
    protected $id;
    protected $deadline;
    protected $assigned_name;
    protected $assigned_phone;
    protected $created_at;
    protected $updated_at;
    // We add the protected attribute matching the State column, within the Tasks table
    protected $state;
    // We do the same for "Refused_At", Accepted_At" and "Completed_At" (cf Log History)
    protected $refused_at;
    protected $accepted_at;
    protected $completed_at;

    // We add constant strings to refer to the different possible states
    const STATE_PENDING = "Pending";
    const STATE_ACCEPTED = "Accepted";
    const STATE_REFUSED = "Refused";
    const STATE_COMPLETED = "Completed";

    public function __construct()
    {
        // Validation is done run by Valitron library
        // We add the state validation

        $this->validates(
            'required',
            ['deadline', 'assigned_name', 'assigned_phone', 'state']
        );
        $this->validates(
            'date',
            ['created_at', 'updated_at', 'refused_at', 'accepted_at', 'completed_at']
        );
        $this->validates(
            'integer',
            ['id']
        );
    }

    public function save()
    {
        // Base class forward all static:: method calls directly to Zend_Db
        if ($this->isValid()) {
            $this->updated_at = gmdate(DATE_FORMAT);
            if ($this->isNew()) {
                $this->created_at = $this->updated_at;
                static::insert('tasks', $this->attributesForDb());
                $this->id = static::lastInsertId();
            } else {
                static::update(
                    'tasks',
                    $this->attributesForDb(),
                    ['id = ?' => $this->id]
                );
            }
            return $this->id;
        }
    }

    public static function findOrInit($id)
    {
        $task = new Task();
        if ($tasksFound = static::where(['id' => (int)$id])) {
            $task = array_pop($tasksFound);
        }
        return $task;
    }


    public static function where($params, $opts = [])
    {
        // Default options' values
        $limit = 0;
        $start = 0;
        $orderBy = ['created_at'];
        $orderDirection = ['asc'];
        extract($opts, EXTR_IF_EXISTS);
        $orderBy = array_flatten([$orderBy]);
        $orderDirection = array_flatten([$orderDirection]);

        // Return value
        $collection = [];
        // Build the query
        list($where, $values) = Builder::expandWhere(
            $params,
            ['wildcard' => true, 'prefix' => 't.']);

        // Select header
        $select = "SELECT t.* FROM tasks AS t";

        // Build order by
        foreach ($orderBy as $i => $value) {
            $dir = isset($orderDirection[$i]) ? $orderDirection[$i] : 'ASC';
            $orderBy[$i] = "`$value` $dir";
        }
        $orderBy = implode(', ', $orderBy);

        $limitClause = '';
        if ($limit) {
            $limitClause = "LIMIT $start, $limit";
        }

        $orderByClause = '';
        if ($orderBy) {
            $orderByClause = "ORDER BY $orderBy";
        }
        if ($where) {
            $where = "WHERE $where";
        }

        $sql = "$select $where $orderByClause $limitClause";
        if ($res = static::fetchAll($sql, $values)) {
            foreach ($res as $row) {
                $collection[] = static::instanceWith($row);
            }
        }
        return $collection;
    }
}
