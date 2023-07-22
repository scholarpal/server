<?php

namespace App\Controllers;

use App\Models\CommentModel;
use CodeIgniter\RESTful\ResourceController;

class Comment extends ResourceController
{
    public function add($school_name)
    {
        $model = new CommentModel();
        $data = [
            'comment' => $_POST['comment'],
            'category_id' => $_POST['category_id'],
            'rating' => $_POST['rating'],
            'user_id' => $_POST['user_id'],
            'school_name' => url_title($school_name),
        ];

        try {
            // return $this->respond($data);
            return $this->respond($model->insert($data), 201);
        } catch (\Throwable $th) {
            return $this->respond($th);
        }
    }

    public function getBySchool($school_name)
    {
        $model = new CommentModel();
        $data = $model->where('school_name', url_title($school_name))->findAll();

        return $this->respond($data);
    }

    public function getByCategory($school_name, $category_name)
    {
        $model = new CommentModel();
        // $categories = $model->db->table('category')->where('name', $category_name)->get()->getFirstRow();

        $data = $model->where('school_name', url_title($school_name))->where('category', $category_name)->findAll();

        // $data = $model->where('categories_id', $categories)->where('school_name', $school_name)->join('school', 'school.id=comments.school_id')->join('user', 'user.id=comments.user_id')->join('rating', 'rating.id=comments.rating_id')->first();

        return $this->respond($data);
    }
}
