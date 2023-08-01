<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model{
    use HasFactory;

    protected $table = 'clients_supports';

    protected $fillable = [
        'client_id',
        'text',
        'is_admin',
    ];

    protected $appends = ['has_answer'];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function getMessagesparseAttribute(){
        if(is_array($this->messages)){
            return $this->messages;
        } else {
            return false;
        }
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function unread(){
        return $this->belongsToMany('App\User', 'user_unread_support_rel');
    }

    public function users(){
        return $this->belongsToMany('App\User', 'user_support_rel');
    }

    public function messages(){
        return $this->hasMany('App\Models\Supportmessage');
    }

    public function getHasAnswerAttribute(){
        $supports = Support::query()->where([
            'client_id' => $this->client_id
        ])->orderByDesc('updated_at')->get();

        if($supports->count() > 0){
            if($supports->first()->is_admin)
                return false;
            else
                return true;
        } else {
            return false;
        }

    }

    public function scopeIsAdmin($query){
        return $query->where('is_admin' , false)->groupBy('client_id')
            ->havingRaw('count("client_id") > 0');
    }

}
