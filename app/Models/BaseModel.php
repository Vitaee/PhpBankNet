<?php

namespace App\Models;

use App\Enums\SortBy;
use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
Generic functions for the CRUD operations on the models
*/

class BaseModel extends Model
{
    use HasFactory;
    // table name override from laravel elaquent model
    protected $table;

    // fillable name override from laravel elaquent model
    protected $fillable;

    // timestamps override from laravel elaquent model
    public $timestamps = true;

    // hidden columns override from laaravel elaquent model
    protected $hidden = [];

    /*
    Create data in the database related database table
    The $keys should include sensitive data that can be unique like email
    The key should exist in the data otherwise it will throw an error!
    in case of keys already exist, response as empty array
    if force = true, that means force to insert same value twice
    */
    public static function Insert(array $data, array $keys = [], bool $force = false): mixed
    {
        if (!$force) {
            $is_exist = self::IsExist($data, $keys);
            if ($is_exist)
                return [];
        }
        return self::create($data);
    }

    /*
    Get only one value with given parameters.
    Columns used for the selecting specific columns, if not given return all(*) except hidden columns.
    Sort by should user if sort column.
    */
    public static function GetOne(
        array $filters,
        array $columns = [],
        int $sort_by = SortBy::UNDEFINED,
        string $sort_column = "",
        mixed $with = null
    ): mixed {
        $model = self::getModel($filters, $columns, $with);
        if ($sort_by != SortBY::UNDEFINED)
            $model = self::Sort($model, $sort_by, $sort_column);
        if ($columns !== [])
            $model = $model->get($columns)->first();
        else
            $model = $model->first();
        if ($model == null)
            return [];
        return $model;
    }

    public static function Search(
        string $key,
        array $search_columns,
        array $columns = [],
        int $sort_by = SortBy::UNDEFINED,
        string $sort_column = "",
        array $pagination = [],
        mixed $with = "",
        bool $like = false,
               $filters = [],

    ) {
        $model = self::SearchLike($columns, $with, $search_columns, $key, $like, $filters);
        $page_number = array_key_exists('page_number', $pagination) ? $pagination["page_number"] : -1;
        $per_page = array_key_exists('per_page', $pagination) ? $pagination["per_page"] : -1;
        if ($sort_by !== SortBy::UNDEFINED)
            $model = self::Sort($model, $sort_by, $sort_column);
        $model = self::Paginate($model, $page_number, $per_page);
        return $model;
    }

    private static function SearchLike(array $columns, mixed $with, array $search_columns, string $search_term, bool $like = false, array $filters = [])
    {
        $model = self::getModel($filters, $columns, $with);
        $i = 0;
        $model = $model
            ->where(function ($query) use ($search_term, $search_columns, $like) {
                foreach ($search_columns as $column) {
                    if ($like)
                        $query->orWhere($column, 'LIKE', '%' . $search_term . '%');
                    else
                        $query->orWhere($column, $search_term);
                }
            });
        return $model;
    }
    /*
    Get all value with given parameters.
    Columns used for the selecting specific columns, if not given return all(*) except hidden columns.
    Sort by should user if sort column.
    */
    public static function GetAll(
        array $filters = [],
        array $columns = [],
        int $sort_by = SortBy::UNDEFINED,
        string $sort_column = "",
        array $pagination = [],
        mixed $with = "",
    ): mixed {
        $model = self::getModel($filters, $columns, $with);
        $page_number = array_key_exists('page_number', $pagination) ? $pagination["page_number"] : -1;
        $per_page = array_key_exists('per_page', $pagination) ? $pagination["per_page"] : -1;
        if ($sort_by !== SortBy::UNDEFINED)
            $model = self::Sort($model, $sort_by, $sort_column);
        $model = self::Paginate($model, $page_number, $per_page);
        return $model;
    }

    public static function CustomGetAll(
        $cb,
        int $sort_by = SortBy::UNDEFINED,
        string $sort_column = "",
        array $pagination = []
    ) {
        $model = $cb();
        $page_number = array_key_exists('page_number', $pagination) ? $pagination["page_number"] : -1;
        $per_page = array_key_exists('per_page', $pagination) ? $pagination["per_page"] : -1;
        if ($sort_by !== SortBy::UNDEFINED)
            $model = self::Sort($model, $sort_by, $sort_column);
        $model = self::Paginate($model, $page_number, $per_page);
        return $model;
    }
    /*
    Delete data with given parameters
    Responsed as boolean.
    */
    public static function DeleteData(array $filters): bool
    {
        $model = self::getModel($filters, []);

        if ($model->first() == [])
            return false;

        if ($model->first()->exists()) {
            $model->first()->delete();
            return true;
        }
        return false;
    }

    public static function DeleteAll(array $filters): bool
    {
        $model = self::getModel($filters, []);
        if ($model->first() == [])
            return false;
        $model->delete();
        return true;
    }
    /*
    Update all data with given filters array.
    In case of not update, response as empty array.
    */
    public static function UpdateData(array $filters, array $data): mixed
    {
        $model = self::getModel($filters, []);

        if ($model->first() == [])
            return [];

        if (!$model->first()->exists())
            return [];
        return $model->first()->update($data);
    }

    /*
     *
     *   check given key is exist, controlling as nested.
     *   data should be array and controlled by model
     *
     */
    public static function IsExist(array $data, array $keys = []): bool
    {
        $model = null;
        if (count($keys) == 0)
            throw new Error("Missing keys to check in IsExist !");

        for ($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];

            if ($i === 0)
                $model = self::where($key, $data[$key]);

            if (!array_key_exists($key, $data))
                throw new Error("Data not include key: " . $key . " on IsExist function !");
            $model = $model->where($key, $data[$key]);
        }
        return $model->exists();
    }

    /*
    Work as where statement for the filters array.
    Each element of array create nested where statement
    */
    public static function getModel(array $filters = [], array $columns = [], mixed $with = null): object
    {

        if (empty($columns)) {
            $columns = ['*'];
        }
        $model = null;
        if (!is_array($with))
            $model = ($with != null) ? self::with($with)->select($columns) : self::select($columns);
        else {
            foreach ($with as $key) {
                if ($model == null)
                    $model = self::with($with)->select($columns);
                else
                    $model = $model->with($with);
            }
        }
        foreach ($filters as $key => $value) {
            $model = $model->where($key, $value);
        }
        return $model;
    }

    /*
    Paginate given database model object to array.
    */
    protected static function Paginate(object $data, int $page_number = -1, int $per_page = -1): array
    {
        $count = $data->count();
        $data_per_page = $page_number != -1 ? ($per_page != -1 ? $per_page : $count) : ($count >= 1 ? $count : 0);
        $page_number = $page_number > 0 ? $page_number : 1;
        $number_of_page = (int) ceil($count / ($data_per_page !== 0 ? $data_per_page : 1));
        $list = $data->skip(($page_number - 1) * $data_per_page)->take($data_per_page);
        if (!empty($list))
            $list = $list->values();
        return [
            "data" => $list,
            "page_number" => $page_number,
            "per_page" => $data_per_page,
            "number_of_data" => $count,
            "number_of_page" => $number_of_page,
        ];
    }

    /*
     *
     * Sort data with given data object, sortby enum and name key. in ATO, ZTOA name_key
     *   should not be null
     */
    protected static function Sort(object $data, int $sort_by, string $name_key = ""): object
    {
        $sort = "ASC";
        switch ($sort_by) {
            case SortBy::CREATIONDATE_ASC:
            case SortBy::FIRSTUPDATED:
            case SortBy::ATOZ:
                $sort = "ASC";
                break;
            case SortBy::CREATIONDATE_DESC:
            case SortBy::LASTUPDATED:
            case SortBy::ZTOA:
                $sort = "DESC";
                break;
        }
        return $data->orderBy($name_key, $sort)->get();
    }
}
