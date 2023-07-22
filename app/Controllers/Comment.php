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
            'rating_id' => $_POST['rating_id'],
            'user_id' => $_POST['user_id'],
            'school_name' => url_title($school_name),
        ];

        try {
            $this->respondCreated($model->insert($data), 'Success');
        } catch (\Throwable $th) {
            $this->respond($th);
        }
    }

    public function getBySchool($school_name = null)
    {
        $model = new CommentModel();
        $school = $model->db->table('sekolah')->where('school', $school_name)->get()->getFirstRow();

        $data = $model->where('schoo', $school['id'])->join('school', 'school.id=comments.school_id')->join('user', 'user.id=comments.user_id')->join('rating', 'rating.id=comments.rating_id')->first();

        return $this->respond($data);
    }

    public function getByCategory($category_name)
    {
        $model = new CommentModel();
        $categories = $model->db->table('category')->where('name', $category_name)->get()->getFirstRow();

        $data = $model->where('categories_id', $categories)->join('school', 'school.id=comments.school_id')->join('user', 'user.id=comments.user_id')->join('rating', 'rating.id=comments.rating_id')->first();

        return $this->respond($data);
    }
}
