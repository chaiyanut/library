<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Book;
use App\Foobar;
use App\from;
use Input;
use Redirect;
use Auth;
use Validator;
use DB;
use Session;
use App\User;
use App\News;

use Mail;

class UserController extends Controller
{
    private $curr_raw_time;
    private $curr_date;
    private $curr_date_time;
    private $day = 86400;

    public function getTest() {
        Mail::send('admin.email', [], function($message) {
            $message->from('library.coe.psu@gmail.com', 'PSU PHUKET CoE Library System');
            $message->to('library.coe.psuo@gmail.com', 'Chaiyanut Teetuan')->subject('Test');
        });
    }

    public function getBookDetail($id) {
        $book = Book::find($id);
        return View('user.bookdetail')->with('book', $book);
    }

    public function __construct() {
        date_default_timezone_set('Asia/Bangkok');
        $this->curr_raw_time = getdate();
        $this->curr_date       = $this->curr_raw_time['year'].'-'.$this->curr_raw_time['mon'].'-'.$this->curr_raw_time['mday'];
        $this->curr_date_time   = $this->curr_raw_time['year'].'-'.$this->curr_raw_time['mon'].'-'.$this->curr_raw_time['mday']. ' '. $this->curr_raw_time['hours']. ':'. $this->curr_raw_time['minutes']. ':'. $this->curr_raw_time['seconds'];
    }


    public function dashboard() {

    	$news = DB::table('news')->orderBy('updated_at', 'desc')->get();
        return View('user.dashboard')->with('news', $news);
    }

    public function getBorrow() {
        if(Input::get('sid') != "") {
            $sid = Input::get('sid');
            $user = DB::table('users')->where('username', $sid)->first();
        } else {
            if (Session::has('sid')) {
                $sid = Session::get('sid');
                $user = DB::table('users')->where('id', $sid)->first();
            } else {
                $user = NULL;
            }
        }
        
        if($user) {
            Session::put('sid', $user->id);
            $books = DB::table('borrows')->where('user_id', $user->id)->get();
            $reserves_id = DB::table('reserves')->where('user_id', $user->id)->get();
            $borrows_date = DB::table('borrows')->where('user_id', $user->id)->get();

            $reserves = array();
            foreach($reserves_id as $book_id) {
                $book = DB::table('books')->where('id', $book_id->book_id)->first();
                array_push($reserves, $book);
            }

            $this->setFined($borrows_date);
            $borrows_date = DB::table('borrows')->where('user_id', $user->id)->get();

            $borrows  = array();
            foreach($borrows_date as $book_id) {
                $book = DB::table('books')->where('id', $book_id->book_id)->first();
                array_push($borrows, $book);
            }
            return View('admin.borrow')->with('user', $user)->with('reserves', $reserves)->with('borrows', $borrows)->with('borrows_date', $borrows_date)->with('reserves_date', $reserves_id);
        }
        return View('admin.borrow');
    }

    public function getBorrowFind() {

        if(Input::get('sid') != "") {
            $sid = Input::get('sid');
            $user = DB::table('users')->where('username', $sid)->first();
        } else {
            if (Session::has('sid')) {
                $sid = Session::get('sid');
                $user = DB::table('users')->where('id', $sid)->first();
            }
        }
        
        if($user) {
            Session::put('sid', $user->id);
            $books = DB::table('borrows')->where('user_id', $user->id)->get();
            $reserves_date = DB::table('reserves')->where('user_id', $user->id)->get();
            $borrows_date = DB::table('borrows')->where('user_id', $user->id)->get();

            $reserves = array();
            foreach($reserves_date as $book_id) {
                $book = DB::table('books')->where('id', $book_id->book_id)->first();
                array_push($reserves, $book);
            }

            $this->setFined($borrows_date);
            $borrows_date = DB::table('borrows')->where('user_id', $user->id)->get();

            $borrows  = array();
            foreach($borrows_date as $book_id) {
                $book = DB::table('books')->where('id', $book_id->book_id)->first();
                array_push($borrows, $book);
            }
        }
        else
            return Redirect::to('borrow');
        
        return View('admin.borrow')->with('user', $user)->with('reserves', $reserves)->with('borrows', $borrows)->with('borrows_date', $borrows_date)->with('reserves_date', $reserves_date);
    }

    public function getBorrowApprove($id) {
        if (Session::has('sid')) {
            $sid = Session::get('sid');
            $reserves_id = DB::table('reserves')->where('user_id', $sid)->where('book_id', $id);

            $book = Book::find($id);
            if($book->status == 'on') {
                DB::table('borrows')->insert([
                    'user_id' => $sid,
                    'book_id' => $id,
                    'created_at' => $this->curr_date,
                    'updated_at' => $this->curr_date
                    ]);
            } else {
                return Redirect::back();
            }

            if($reserves_id->count())
                $reserves_id->delete();

            $book = Book::find($id);
            $book->status = "off";
            $book->reserve = "on";
            $book->save();
            return Redirect::to('borrow');
        }
    }

    public function getReturnApprove($id) {
        if (Session::has('sid')) {
            $sid = Session::get('sid');
            $borrow_id = DB::table('borrows')->where('user_id', $sid)->where('book_id', $id);
            if($borrow_id->count()) {
                $borrow_id->update(['returned' => 1, 'updated_at' => $this->curr_date_time]);
                $book = Book::find($id);
                $book->status  = "on";
                $book->reserve = "off";
                $book->save();
                return Redirect::to('borrow');
            } else {
                return Redirect::back();
            }
            
        }
    }

    public function getLogout() {
        Session::forget('sid');
        return Redirect::to('auth/logout');
    }

	public function search() {
        /** Login check **/
        if(!Auth::check())
            return Redirect::to('auth/login');
        /*****************/
        
    	$books = Book::paginate(50);
        $books->setPath('search');
    	return View('user.search')->with('books',$books);
    }

    public function reserve($id) {
        /** Login check **/
        if(!Auth::check())
            return Redirect::to('auth/login');
        /*****************/

        $book = Book::find($id);

        if($book->reserve == 'off') {
            if(DB::table('reserves')->where('user_id', Auth::user()->id)->count() < 3) {
                DB::table('reserves')->insert([
                    'user_id'    => Auth::user()->id,
                    'book_id'    => $book->id,
                    'created_at' => $this->curr_date_time,
                    'updated_at' => $this->curr_date_time
                    ]);
                $book->reserve = "on";
                $book->save();
            } else {
                return Redirect::back();
            }
        }
        return Redirect::to('service');
    }

    public function removeReserve($id) {
        /** Login check **/
        if(!Auth::check())
            return Redirect::to('auth/login');
        /*****************/

        DB::table('reserves')->where('user_id', Auth::user()->id)->where('book_id', $id)->delete();
        $book = Book::find($id);
        $book->reserve = "off";
        $book->save();

        return Redirect::back();
    }

    public function postCodeFind() {
        $keyword = Input::get('keyword') . '%';
        $search = DB::table('books')
                ->where('code', 'like', $keyword)->orderBy('id', 'desc')
                ->get();

        if(Input::get('sid') != "") {
            $sid = Input::get('sid');
            $user = DB::table('users')->where('username', $sid)->first();
        } else {
            if (Session::has('sid')) {
                $sid = Session::get('sid');
                $user = DB::table('users')->where('id', $sid)->first();
            } else {
                $user = NULL;
            }
        }
        
        if($user) {
            Session::put('sid', $user->id);
            $books = DB::table('borrows')->where('user_id', $user->id)->get();
            $reserves_id = DB::table('reserves')->where('user_id', $user->id)->get();
            $borrows_id = DB::table('borrows')->where('user_id', $user->id)->get();

            $reserves = array();
            foreach($reserves_id as $book_id) {
                $book = DB::table('books')->where('id', $book_id->book_id)->first();
                array_push($reserves, $book);
            }

            $borrows  = array();
            foreach($borrows_id as $book_id) {
                $book = DB::table('books')->where('id', $book_id->book_id)->first();
                array_push($borrows, $book);
            }
            return View('admin.borrow')->with([ 'user' => $user, 
                                            'reserves' => $reserves, 
                                            'borrows' => $borrows, 
                                            'borrows_date' => $borrows_id, 
                                            'search'=>$search, 
                                            'keyword'=>Input::get('keyword')]);
        }
        return View('admin.borrow');
    }

    private function setFined($borrow_list) {
        foreach($borrow_list as $bl) {
            $fined = ($this->dateDiff($bl->created_at, $this->curr_date)) - 5;
            if($fined > 0 &&  $bl->returned == 0) {
                DB::table("borrows")->where("id", $bl->id)->update(["fined" => $fined * 10]);
            }
        }
    }

    private function dateDiff($left, $right)  {
        return (strtotime($right) - strtotime($left))/$this->day;
    }


    public function getService() {
        /** Login check **/
        if(!Auth::check())
            return Redirect::to('auth/login');
        /*****************/
        $books = array();
        $borrows = array();
        $reserves = DB::table('reserves')->where('user_id', Auth::user()->id)->get();
        
        if($reserves) {
            foreach($reserves as $reserve) {
                if(((time()-strtotime($reserve->created_at))/(60*60*24)) > 3) {
                    DB::table('reserves')->where('id', $reserve->id)->delete();
                    $book = DB::table('books')->where('id', $reserve->book_id)->update(['reserve' => 'off']);
                } else {
                    $book = DB::table('books')->where('id', $reserve->book_id)->first();
                    array_push($books, $book);
                }
            }
        }

        $borrows_date = DB::table('borrows')->where('user_id', Auth::user()->id)->get();
        //var_dump($borrows_date);
        $this->setFined($borrows_date);
        //$borrows_date = DB::table('borrows')->where('user_id', Auth::user()->id)->get();

        foreach($borrows_date as $borrow) {
            $book = DB::table('books')->where('id', $borrow->book_id)->first();
            array_push($borrows, $book);
        }

        return View('user.service')->with([ 'reserves' => $reserves,
                                            'books' => $books,
                                            'borrows' => $borrows,
                                            'borrows_date' => $borrows_date]);
    }

    public function postSearch() {
        /** Login check **/
        if(!Auth::check())
            return Redirect::to('auth/login');
        /*****************/
        
        $cat = Input::get('cat');
        $keyword = Input::get('keyword') . '%';
        $books = DB::table('books')
                ->where($cat, 'like', $keyword)->orderBy('id', 'desc')
                ->paginate(50);

        //$books = Book::paginate(50);
        $books->setPath('search');
        return View('user.search')->with(['books'=>$books, 'keyword'=>Input::get('keyword'), 'cat'=>$cat]);
        //return View('user.search')->with('books',$books)->with('keyword', Input::get('keyword'))->with('cat', $cat);
    }

    public function home() {

        $news = DB::table('news')->orderBy('updated_at', 'desc')->get();
        $suggests = DB::table('suggests')->get();

        $books = array();
        foreach($suggests as $suggest) {
            $book = DB::table('books')->where('id', $suggest->book_id)->first();
            array_push($books, $book);
        }
        
        $borrows = array();
        $borrows_date = DB::table('borrows')->where('user_id', Auth::user()->id)->get();
        foreach($borrows_date as $borrow) {
            $book = DB::table('books')->where('id', $borrow->book_id)->first();
            array_push($borrows, $book);
        }

        //var_dump($borrows);

        return View('user.dashboard')->with(['news' => $news, 
                                             'suggests' => $suggests,
                                             'books' => $books, 
                                             'borrows' => $borrows,
                                             'borrows_date' => $borrows_date]);
    }

    public function webboard() {
        $questions = DB::table('webboard')->orderBy('updated_at', 'desc')->get();
        return View('user.webboard')->with('questions', $questions);
    }

    public function deleteQuestion($id) {
        /** Login check **/
        if(!Auth::check())
            return Redirect::to('auth/login');
        /*****************/

        $question = DB::table('webboard')->where('id', $id)->delete();
        return Redirect::back();

    }

    public function viewQuestion($id) {
        $question = DB::table('webboard')->where('id', $id);

        if(!$question->count()) return Redirect::to('webboard');
        $update = DB::table('webboard')->where('id', $id)->increment('view');
        $reply = DB::table('reply')->where('question_id', $id)->get();
        return View('user.viewquestion')->with('question', $question->first())->with('reply', $reply);
    }

    public function postViewQuestion() {
        $id = Input::get('id');
        $question = DB::table('webboard')->where('id', $id)->count();

        if(!$question) return Redirect::to('webboard');

        $curr_raw_time   = getdate();
        $curr_date_time   = $curr_raw_time['year'].'-'.$curr_raw_time['mon'].'-'.$curr_raw_time['mday']. ' '. $curr_raw_time['hours']. ':'. $curr_raw_time['minutes']. ':'. $curr_raw_time['seconds'];

        $rules = array('reply'   => 'required');
        $input = Input::all();
        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            return Redirect::to('webboard');
        }

        DB::table('reply')->insert(
            [
            'question_id' => $id,
            'details' => Input::get('reply'),
            'user_id' => Auth::user()->id,
            'created_at' => $curr_date_time
            ]
        );

        $update = DB::table('webboard')->where('id', $id)->increment('reply');

        return  Redirect::back();
    }

    public function newQuestion() {
        return View('user.newquestion');
    }

    public function postNewQuestion() {
        $curr_raw_time   = getdate();
        $curr_date_time   = $curr_raw_time['year'].'-'.$curr_raw_time['mon'].'-'.$curr_raw_time['mday']. ' '. $curr_raw_time['hours']. ':'. $curr_raw_time['minutes']. ':'. $curr_raw_time['seconds'];

        $rules = array(
                'question'   => 'required',
                'detail'   => 'required',
                'name'   => 'required',
                  );
        $input = Input::all();
        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            return Redirect::to('newquestion')->with(['message'=>'detail  not!', 'alert' => 'danger']);
        }

        DB::table('webboard')->insert(
            ['question' => Input::get('question'), 'details' => Input::get('detail'), 'user_id' => Auth::user()->id, 'name' => Input::get('name'), 'created_at' => $curr_date_time]
        );
        return Redirect::to('webboard');
    }

    public function news($id) {
        $news = DB::table('news')->where('id', $id)->first();
        if(!$news) return Redirect::to('home');
        return View('user.news')->with('news', $news);
    }

    public function getUpdateNews($id) {
        $news = News::find($id);
        return view('user.update-news')->with(['news' => $news]);
    }

    public function postUpdateNews($id) {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/

        $news = News::find($id);
        $data = Input::all();

        $curr_raw_time   = getdate();
        $curr_date_time   = $curr_raw_time['year'].'-'.$curr_raw_time['mon'].'-'.$curr_raw_time['mday']. ' '. $curr_raw_time['hours']. ':'. $curr_raw_time['minutes']. ':'. $curr_raw_time['seconds'];

        $rules = array(
            'detail'   => 'required',
        );
        $input = Input::all();
        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            return Redirect::to('createnews')->with(['message'=>'detail  not!', 'alert' => 'danger']);
        }

        if (Input::file('image')->isValid()) {

            if(file_exists('assets/uploads/'.$news->img)) {
                unlink('assets/uploads/'.$news->img);
            }

            $destinationPath = 'assets/uploads'; // upload path
            $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
            $fileName = 'img_' . time() . '_' . rand(00000000000000000000,999999999999999999).'.'.$extension; // renameing image
            Input::file('image')->move($destinationPath, $fileName); // uploading file to given path

            $news->title = $data['title'];
            $news->detail = $data['detail'];
            $news->img = $fileName;
            $news->updated_at = $curr_date_time;
            $news->save();

            // sending back with message
            Session::flash('success', 'Upload successfully');
            return "Upload success.";
        } else {
            // sending back with error message.
            return 'Uploaded file is not valid.';
        }

        return Redirect::to('home');
    }

    public function postDeleteNews() {
        /** Login check **/
        if(!Auth::check())
            return Redirect::to('auth/login');
        /*****************/

        $dump = DB::table('news')->where('id', Input::get('id'))->delete();
        return Redirect::to('home');
    }

    public function from() {
         $books = DB::table('books')
                ->where('code', 'like', 'rp%')
                ->orderBy('id', 'desc')
                ->paginate(50);
        $books->setPath('from');

        return View('user.from')->with('books', $books);
    }

    public function journal() {
         $books = DB::table('books')
                ->where('code', 'like', 'j%')
                ->orderBy('id', 'desc')
                ->paginate(50);
        $books->setPath('journal');

        return View('user.journal')->with('books', $books);
    }

     public function technology() {
        $books = DB::table('books')
                ->where('code', 'like', 'c%')
                ->orWhere('code', 'like', 'i%')
                ->orWhere('code', 'like', 'n%')
                ->orWhere('code', 'like', 'r%')
                ->orWhere('code', 'like', 'g%')
                ->orderBy('id', 'desc')
                ->paginate(50);
        $books->setPath('technology');
       
        return View('user.technology')->with('books', $books);
    }

    public function technologyFilter($id) {
        switch ($id) {
            case 1: $keyword = 'c%';
                    break;
            case 2: $keyword = 'g%';
                    break;
            case 3: $keyword = 'i%';
                    break;
            case 4: $keyword = 'n%';
                    break;
            case 5: $keyword = 'r%';
                    break;
            default:
                $keyword = '%';
                break;
        }
        $books = DB::table('books')
                ->where('code', 'like', $keyword)
                ->orderBy('id', 'desc')
                ->paginate(50);
        $books->setPath('technology');
       
        return View('user.technology')->with('books', $books);
    }

    public function insert() {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/

        return View('user.insert');
    }

    public function viewprofile() {
        return View('user.viewprofile');
    }
   


    public function editprofile() {
        return View('user.editprofile');
    }

     public function editbook() {
        return View('user.editbook');
    }


     public function createnews() {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/
        return View('user.createnews');
    }

    public function getCreateBooks() {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/
        return View('user.createbooks');
    }

    public function postCreateBooks() {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/
        // getting all of the post data
        $file = array('image' => Input::file('image'));
        // setting up rules
        $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, $rules);
        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return Redirect::to('upload')->withInput()->withErrors($validator);
        } else {
            // checking file is valid.
            if (Input::file('image')->isValid()) {
                $destinationPath = 'assets/uploads'; // upload path
                $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = 'img_' . time() . '_' . rand(00000000000000000000,999999999999999999).'.'.$extension; // renameing image
                Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
                
                $suggest_book = DB::table('suggests')->insert([
                    'book_id'   => DB::table('books')->where('code', Input::get('code'))->first()->id,
                    'img'       => $fileName
                    ]);

                // sending back with message
                Session::flash('success', 'Upload successfully');
                return 'Upload image success.';
            } else {
                // sending back with error message.
                return 'Uploaded file is not valid.';
            }
        }
    }

    public function getSuggestBook() {
        $keyword = Input::get('term') . '%';
        $result = DB::table('books')->where('code', 'like', $keyword)->take(10)->get();
        //var_dump($result);
        foreach($result as $item) {  
            $json_data[]=array(    
                "id"=> $item->id,
                "label"=> $item->code,
                "value"=> $item->code,
            );      
        }    
        $json= json_encode($json_data);    
        echo $json;
    }

    public function getDeleteSuggest($id) {
        $sbook = DB::table('suggests')->where('book_id', $id)->delete();
        return Redirect::to('home');
    }

    public function postCreatenews() {
        
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/

        $curr_raw_time   = getdate();
        $curr_date_time   = $curr_raw_time['year'].'-'.$curr_raw_time['mon'].'-'.$curr_raw_time['mday']. ' '. $curr_raw_time['hours']. ':'. $curr_raw_time['minutes']. ':'. $curr_raw_time['seconds'];

        $rules = array(
            'detail'   => 'required',
        );
        $input = Input::all();
        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            return Redirect::to('createnews')->with(['message'=>'detail  not!', 'alert' => 'danger']);
        }

        if (Input::file('image')->isValid()) {

            $destinationPath = 'assets/uploads'; // upload path
            $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
            $fileName = 'img_' . time() . '_' . rand(00000000000000000000,999999999999999999).'.'.$extension; // renameing image
            Input::file('image')->move($destinationPath, $fileName); // uploading file to given path

            $insert = DB::table('news')->insert(
                ['title' => Input::get('title'), 
                'detail' => Input::get('detail'), 
                'created_at' => $curr_date_time,
                'img'       => $fileName
                ]);

            // sending back with message
            Session::flash('success', 'Upload successfully');
            return 'Upload image success.';
        } else {
            // sending back with error message.
            return 'Uploaded file is not valid.';
        }

        return Redirect::to('home');
    }


    public function postInsert() {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/

        $rules = array(
                'code'    => 'required',
                'name'    => 'required',
                'category'    => 'required',
                'year'    => 'required|digits:4',
                'isbn'    => 'required',
                'author'    => 'required',
                'edition'    => 'required',
                'publisher'    => 'required',
                );
        $input = Input::all();
        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            return Redirect::to('insert')->with(['message'=>'All fild are require. !!', 'alert' => 'danger']);
        }

        $book = new Book();
        $book->code = Input::get('code');
        $book->name = Input::get('name');
        $book->category = Input::get('category');
        $book->year = Input::get('year');
        $book->ISBN = Input::get('isbn');
        $book->author = Input::get('author');
        $book->edition = Input::get('edition');
        $book->publisher = Input::get('publisher');
        $book->save();
        return Redirect::to('search');
    }

    public function delete() {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/

        $books = Book::paginate(50);
        $books->setPath('delete');
        return View('user.search')->with('books',$books)->with('delete', 1);
    }

    public function postDelete() {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/

        $checkbox = Input::get('checkbox');
        $count = count($checkbox);

        for($i = 0; $i < $count; $i++) {
            $id = (int) $checkbox[$i]; // Parse your value to integer
            Book::destroy($id);
        }
        return Redirect::back();
    }

    public function edit($id) {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/
        $book = Book::find($id);
        return View('user.edit')->with('book',$book);
    }



    public function postEdit($id) {
        /** Login check **/
        if(!$this->checkAdmin())
            return Redirect::to('auth/login');
        /*****************/
        $book = Book::findOrFail($id);
        $book->code = Input::get('code');
        $book->name = Input::get('name');
        $book->category = Input::get('category');
        $book->year = Input::get('year');
        $book->ISBN = Input::get('ISBN');
        $book->author = Input::get('author');
        $book->edition = Input::get('edition');
        $book->publisher = Input::get('publisher');
        $book->status = Input::get('status');
        $book->reserve = Input::get('reserve');

        $book->save();
        return Redirect::to('search');
    }

    private function checkAdmin() {
        return (Auth::check() && Auth::user()->role == 'admin');
    }

    public function getDemo() {
        $user = new User(1);
        var_dump($user->Authenticate('5535512015', 'bn@5535512015'));

        echo $user->getPid() . " " . $user->getFirstname() . " " . $user->getLastname();
        echo "<br>";
        return "<br>";
    }

    public function getLogin() {
        return view('auth.Login');
    }

    public function postLogin() {
        $data = Input::all();

        $validator = Validator::make($data, [
            'username' => 'required|digits:10',
        ]);


        // admin id validate
        if ($validator->fails()) {
            // Admin login
            $validator = Validator::make($data, [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back();
            } else {
                // admin validate
                if(Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
                    echo "Login success.";
                    return Redirect::to('home');
                } else {

                    //teacher login
                    $soap_user = new User(1);
                    if($soap_user->Authenticate($data['username'], $data['password'])) {
                        $user = User::firstOrCreate(['username' => $data['username']]);
                        $user->name  = $soap_user->getFirstname() . " " . $soap_user->getLastname();
                        $user->email = $soap_user->getEmail();
                        $user->username = $data['username'];
                        $user->role = "teacher";
                        $user->password = bcrypt($soap_user->getPid());
                        $user->save();
                        if(Auth::attempt(['username' => $data['username'], 'password' => $soap_user->getPid()])) {
                            echo "Login success.";
                            return Redirect::to('home');
                        } else {
                            echo "Login fail!";
                            return Redirect::back();
                        }
                        
                    } else {
                        echo "Login fail!!";
                        return Redirect::back();
                    }
                }
            }

        // student id validate
        } else {
            // Student Login
            $validator = Validator::make($data, [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back();
            } else {
                $soap_user = new User(1);
                if($soap_user->Authenticate($data['username'], $data['password'])) {
                    $user = User::firstOrCreate(['username' => $data['username']]);
                    $user->name  = $soap_user->getFirstname() . " " . $soap_user->getLastname();
                    $user->email = $soap_user->getEmail();
                    $user->username = $data['username'];
                    $user->password = bcrypt($soap_user->getPid());
                    $user->save();
                    if(Auth::attempt(['username' => $data['username'], 'password' => $soap_user->getPid()])) {
                        echo "Login success.";
                        return Redirect::to('home');
                    } else {
                        echo "Login fail!";
                        return Redirect::back();
                    }
                    
                } else {
                    echo "Login fail!!";
                    return Redirect::back();
                }
            }
        }
    }

    public function getRegister() {
        return view('auth.register');
    }

    public function postRegister() {
        $data = Input::all();
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->password = bcrypt($data['password']);
        $user->role = 'admin';
        $user->save();
        return Redirect::to('auth/login');
    }

}
