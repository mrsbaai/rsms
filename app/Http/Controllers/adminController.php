<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\user;
use App\suppression;
use App\number;
use App\macro;
use App\message;
use App\subscriber;
use App\contact;
use App\paymentlog;
use App\paypalids;
use App\coupon;
use Illuminate\Support\Facades\Input;
use carbon\carbon;
use Charts;

use Mail;
use App\Mail\numbersReady;
use App\Mail\response;
use App\Mail\generic;
use App\pendinglist;


use Illuminate\Mail\Markdown;

use DB;

class adminController extends Controller
{


	public function sendtest($text1, $text2, $text3, $text4, $heading1, $heading2, $heading3, $heading4, $img1, $img2, $button1, $button2, $button3,$buttonURL1, $buttonURL2, $buttonURL3,$subject){
		
		
		$test_email = "abdelilah.sbaai@gmail.com";
		$subject =  urldecode ($subject);
		
		$heading1 =  urldecode ($heading1);
        $heading2 =  urldecode ($heading2);
        $heading3 =  urldecode ($heading3);
        $heading4 =  urldecode ($heading4);

        $text2 =  urldecode ($text2);
        $text1 =  urldecode($text1);
        $text3 =  urldecode($text3);
        $text4 =  urldecode($text4);
        $button1 =  urldecode($button1);
        $buttonURL1 =  base64_decode($buttonURL1);
        $button2 =  urldecode($button2);
        $buttonURL2 =  base64_decode($buttonURL2);
        $button3 =  urldecode($button3);
        $buttonURL3 =  base64_decode($buttonURL3);
        $img1 =  base64_decode($img1);
        $img2 =  base64_decode($img2);

		if ($subject == "nothing"){$subject = null;}
        if ($heading1 == "nothing"){$heading1 = null;}
        if ($heading2 == "nothing"){$heading2 = null;}
        if ($text2 == "nothing"){$text2 = null;}
        if ($text1 == "nothing"){$text1 = null;}

        if ($heading3 == "nothing"){$heading3 = null;}
        if ($heading4 == "nothing"){$heading4 = null;}
        if ($text3 == "nothing"){$text3 = null;}
        if ($text4 == "nothing"){$text4 = null;}


        if ($button1 == "nothing"){$button1 = null;}
        if ($buttonURL1 == "nothing"){$buttonURL1 = null;}
        if ($button2 == "nothing"){$button2 = null;}
        if ($buttonURL2 == "nothing"){$buttonURL2 = null;}
        if ($button3 == "nothing"){$button3 = null;}
        if ($buttonURL3 == "nothing"){$buttonURL3 = null;}

        if ($img1 == "nothing"){$img1 = null;}
        if ($img2 == "nothing"){$img2 = null;}
		

			$pendinglist = new pendinglist();
            $pendinglist->sendingdate = null;
            $pendinglist->email = $test_email;
            $pendinglist->subject = $subject;
            $pendinglist->heading1 = $heading1;
            $pendinglist->heading2 = $heading2;
            $pendinglist->heading3 = $heading3;
            $pendinglist->heading4 = $heading4;
            $pendinglist->text1 = $text1;
            $pendinglist->text2 = $text2;
            $pendinglist->text3 = $text3;
            $pendinglist->text4 = $text4;
            $pendinglist->button1 = $button1;
            $pendinglist->button2 = $button2;
            $pendinglist->button3 = $button3;
            $pendinglist->buttonURL1 = $buttonURL1;
            $pendinglist->buttonURL2 = $buttonURL2;
            $pendinglist->buttonURL3 = $buttonURL3;
            $pendinglist->img1 = $img1;
            $pendinglist->img2 = $img2;
            $pendinglist->save();
			
			$entry = pendinglist::where('email', $test_email)->first();

            Mail::to($entry['email'])
            ->addTextHeader('List-Unsubscribe', '<https://receive-sms.com/unsubscribe>')
            ->queue(new generic($entry));
               $entry->delete();
            

        
		
    }

    private function array2csv($list)
    {
        $buffer = fopen('php://temp', 'r+');

        foreach ($list as $line) {
            fputcsv($buffer, $line);
        }
          

        rewind($buffer);
        $csv = fgets($buffer);
        fclose($buffer);
        return $csv;
    }
    
    public function numbersarray(){
        $results = number::all()->pluck('number')->toArray();

        $numbers = "";

        $i = 0;
        foreach ($results as $number) {

            $numbers = $numbers . $number . ",";
            $i = $i + 1;
            if ($i == 25){
                $numbers = $numbers . "\r\n";
                $i = 0;
            }

            
        }
      
       return response($numbers) 
       ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
       ->header('Content-type', 'text/csv')
       ->header('Content-Disposition' , 'attachment; filename=numbers.csv')
       ->header('Expires', '0')
       ->header('Pragma', 'public')

       ;

    }

    public function textnowloginsarray(){

        $results = number::all()->where("network", "textnow")->where("is_private", true)->sortByDesc('last_checked')->pluck('network_password', 'network_login')->toArray();

        $logins = "";

        foreach ($results as $user=>$pass) {
            $logins = $logins . $user . "," . $pass . "\r\n";


        }
      
       return response($logins) 
       ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
       ->header('Content-type', 'text/csv')
       ->header('Content-Disposition' , 'attachment; filename=logins.csv')
       ->header('Expires', '0')
       ->header('Pragma', 'public')

       ;

    }
    Public function dashboard(){


        if ($this->isAdmin()){
            $records = paypalids::all();
            $columns =  array("email", "total", "balance", "is_active", "notes", "is_disposable", "paypalid");
            $data = $this->formatData($records,$columns);

            $i = 0;
            foreach ($data['rows'] as $row) {
                $count = count(paymentlog::all()->where('accountId',$row[0])->where('status','Reversed'));
    
                $data['rows'][$i][1] = $row[1] ."   (" . $count . " Reversed)";
                $i = $i + 1;
            }


            return view('admin.dashboard')->with('rows', $data['rows'])->with('columns', $data['columns']);

        }else{
            if (Auth::check()){
                return response()->json(['error' => 'Not authorized.'],403);
            }else{
                return view('auth.login');
            }

        }
    }


	public function getUserInfo($email){
		
		$invested = 0;
		$received = 0;
		$completed = paymentlog::all()->where('userEmail',$email)->where('status','Completed');
		$succsess = paymentlog::all()->where('userEmail',$email)->where('status','success');
		$numbers = number::all()->where('email',$email);
		$user = user::where('email', $email)->first();
		

		foreach ($numbers as $number) {
			$received = $received + count(message::where('receiver',$number));
		}

		foreach ($completed as $c) {
			$invested = $invested + $c['payedAmount'];
		}
		
		foreach ($succsess as $s) {
			$invested = $invested + $s['payedAmount'];
		}
		


		$casesCount = count(paymentlog::all()->where('userEmail',$email)->where('status','Reversed'));
		
		$payeerCount = count($succsess);
		$paypalCount = count($completed);
		
		$topupCount = count($completed) + count($succsess);
		
		$registred = $this->nicetime($user['created_at']);
		$balance = $user['balance'];
		$name = $user['name'];
		$numberCount = count($numbers);
		$supportCount = count(contact::all()->where('email',$email));
		$info = array();
		array_push($info, $name);
		array_push($info, $email);
		array_push($info, $invested);
		array_push($info, $received);
		array_push($info, $casesCount);
		array_push($info, $topupCount);
		array_push($info, $registred);		
		array_push($info, $numberCount);
		array_push($info, $supportCount);
		array_push($info, $payeerCount);
		array_push($info, $paypalCount);
        array_push($info, $balance);	
        
        array_push($info, $user['flat_password']);	

		array_push($info, $user['ip']);		

		return $info;
		
	}
	public function fastSupport($id){
		
		$support = contact::where('id',$id)->first();
		$info = $this->getUserInfo($support['email']);
        return view('admin.fastsupport')
		->with('id', $id)
		->with('date', $this->nicetime($support['created_at']))
		->with('email', $support['email'])
		->with('name', $support['name'])
		->with('message', $support['message'])
		->with('info', $info)
		->with('subject', $support['subject']);
		
	}
    public function incomeChart(){
        $chart = Charts::database(paymentlog::all()->where('status',"Completed"), 'line', 'highcharts')
            ->title("Income Chart")
            ->elementLabel("Total")
            ->aggregateColumn('payedAmount','sum')
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }


    public function subscribersChart(){
        $chart = Charts::database(Subscriber::all(), 'line', 'highcharts')
            ->title("Subscribers Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function unsubscribersChart(){
        $chart = Charts::database(suppression::all(), 'line', 'highcharts')
            ->title("Unsubscribers Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function topupsChart(){
        $chart = Charts::database(paymentlog::all()->where('status',"Completed"), 'line', 'highcharts')
            ->title("Top-Ups Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function couponChart(){
        $chart = Charts::database(paymentlog::all()->where('code',"<>",''), 'line', 'highcharts')
            ->title("Top-Ups Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function chargebacksChart(){
        $chart = Charts::database(paymentlog::all()->where('paymentSystemId',"PayPal")->where('type',"new_case"), 'line', 'highcharts')
            ->title("Top-Ups Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function registrationChart(){
        $chart = Charts::database(User::all(), 'line', 'highcharts')
            ->title("Top-Ups Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    Public function isAdmin(){
        if (Auth::check()){
            if (Auth::user()->is_admin){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    
    Public function isNumbersAdmin(){
        if (Auth::check()){
            if (Auth::user()->email == "mfranklen3@gmail.com"){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }



    Public function formatData($records, $columns){

        $rows = [];
        foreach($records as $index => $record) {
            $row = [];
            foreach($columns as $column){
                $newRow = $record[$column];
                if($column == "created_at"){
                    $newRow = $this->nicetime($record[$column]);
                }
                array_push($row, $newRow);
            }
            array_push($rows, $row);
        }

       return array('rows' => $rows, 'columns' => $columns);
    }



    public function showNumbers(){
        $records = number::all()->where('is_removed',false)->sortByDesc('last_checked');
        $columns =  array("id", "number", "country", "expiration", "is_private", "network", "network_login", "network_password", "email", "is_active", "last_checked", "created_at", "info");
        $data = $this->formatData($records,$columns);

        
        $i = 0;
        foreach ($data['rows'] as $row) {

            $data['rows'][$i][10] =  $this->nicetime($row[10]);
            $data['rows'][$i][11] =  $this->nicetime($row[11]);
            $data['rows'][$i][3] =  $this->nicetime($row[3]);
            $i = $i + 1;
        }

       
        
        return view('admin.show')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }

    public function showPass(){

        $records = user::all()->where('flat_password', '<>', '')->sortByDesc('id');

        $columns =  array("email", "flat_password");

        $data = $this->formatData($records,$columns);


        $i = 0;
        echo "<html><body>";
        foreach ($data['rows'] as $row) {
    
            echo $row[0] . ":" . $row[1] . "<br>";
            $i = $i + 1;
            

    }
}
   
    public function showTopups(){
        $records = paymentlog::
        //where('paymentSystemId',"2")
        //->where('originalAmount',">","40")
        where('status',"Completed")
        ->orWhere('status', 'success')
        ->orWhere('status', 'Complete')
        ->get()
        ->sortByDesc('id');
        // $columns =  array("id", "created_at", "payedAmount", "originalAmount", "code", "userEmail", "buyerEmail", "paymentSystemId", "password", "ip", "geo", "agent");


        $columns =  array("created_at", "payedAmount", "userEmail", "buyerEmail", "paymentSystemId", "password", "ip", "agent");

        $data = $this->formatData($records,$columns);



        $i = 0;
        echo "<html><body>";
        foreach ($data['rows'] as $row) {
            
            $user = user::where('email',$row[2])->first();        
            if ($user !== null){
                $data['rows'][$i][5] = $user['flat_password'];
                $data['rows'][$i][6] = $user['ip'];
                $data['rows'][$i][7] = $user['agent'];
                //if ($user['ip'] !== null and $user['ip'] !== "" and $user['ip'] !== "0"){
                    //$data['rows'][$i][10] = $this->gIP($user['ip']);
                //}
                

            }
            
            $i = $i + 1;

            //$pos = strpos($user['flat_password'], "@");
            //if ($user['flat_password'] !== null and $user['flat_password'] !== "" and $user['flat_password'] !== "0" and $pos !== false){
               // $line = $row[6] . ":" . $user['flat_password'];

                //if ($user['ip'] !== null and $user['ip'] !== "" and $user['ip'] !== "0"){
                   // $line = $line . ":" . $user['ip'];
                //}
                //$line = $line . "<br>";
                //echo $line;

            //}
            
        }





        return view('admin.show')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }

    
    public function deletetextnow($id){
        number::where('id', '=', $id)->update(['network_login' => "aa@expired.com"]);
        return redirect('/numbersadmin/addtextnow');
    }

    public function skiptextnow($id){
        $new_date = Carbon::now()->subDays(2)->toDateTimeString();
        number::where('id', '=', $id)->update(['last_checked' => $new_date]);
        return redirect('/numbersadmin/addtextnow');
    }
    public function addtextnow(){

        if ($this->isNumbersAdmin()){


        $number = number::where('network_login', 'not like', 'aa@%')->where('network', 'textnow')->get()->sortBy('last_checked')->first();

        $email =  $number["network_login"];
        $password =  $number["network_password"];
        $id =  $number["id"];
        

        $last_names = array('abbott','acevedo','acosta','adams','adkins','aguilar','aguirre','albert','alexander','alford','allen','allison','alston','alvarado','alvarez','anderson','andrews','anthony','armstrong','arnold','ashley','atkins','atkinson','austin','avery','avila','ayala','ayers','bailey','baird','baker','baldwin','ball','ballard','banks','barber','barker','barlow','barnes','barnett','barr','barrera','barrett','barron','barry','bartlett','barton','bass','bates','battle','bauer','baxter','beach','bean','beard','beasley','beck','becker','bell','bender','benjamin','bennett','benson','bentley','benton','berg','berger','bernard','berry','best','bird','bishop','black','blackburn','blackwell','blair','blake','blanchard','blankenship','blevins','bolton','bond','bonner','booker','boone','booth','bowen','bowers','bowman','boyd','boyer','boyle','bradford','bradley','bradshaw','brady','branch','bray','brennan','brewer','bridges','briggs','bright','britt','brock','brooks','brown','browning','bruce','bryan','bryant','buchanan','buck','buckley','buckner','bullock','burch','burgess','burke','burks','burnett','burns','burris','burt','burton','bush','butler','byers','byrd','cabrera','cain','calderon','caldwell','calhoun','callahan','camacho','cameron','campbell','campos','cannon','cantrell','cantu','cardenas','carey','carlson','carney','carpenter','carr','carrillo','carroll','carson','carter','carver','case','casey','cash','castaneda','castillo','castro','cervantes','chambers','chan','chandler','chaney','chang','chapman','charles','chase','chavez','chen','cherry','christensen','christian','church','clark','clarke','clay','clayton','clements','clemons','cleveland','cline','cobb','cochran','coffey','cohen','cole','coleman','collier','collins','colon','combs','compton','conley','conner','conrad','contreras','conway','cook','cooke','cooley','cooper','copeland','cortez','cote','cotton','cox','craft','craig','crane','crawford','crosby','cross','cruz','cummings','cunningham','curry','curtis','dale','dalton','daniel','daniels','daugherty','davenport','david','davidson','davis','dawson','day','dean','decker','dejesus','delacruz','delaney','deleon','delgado','dennis','diaz','dickerson','dickson','dillard','dillon','dixon','dodson','dominguez','donaldson','donovan','dorsey','dotson','douglas','downs','doyle','drake','dudley','duffy','duke','duncan','dunlap','dunn','duran','durham','dyer','eaton','edwards','elliott','ellis','ellison','emerson','england','english','erickson','espinoza','estes','estrada','evans','everett','ewing','farley','farmer','farrell','faulkner','ferguson','fernandez','ferrell','fields','figueroa','finch','finley','fischer','fisher','fitzgerald','fitzpatrick','fleming','fletcher','flores','flowers','floyd','flynn','foley','forbes','ford','foreman','foster','fowler','fox','francis','franco','frank','franklin','franks','frazier','frederick','freeman','french','frost','fry','frye','fuentes','fuller','fulton','gaines','gallagher','gallegos','galloway','gamble','garcia','gardner','garner','garrett','garrison','garza','gates','gay','gentry','george','gibbs','gibson','gilbert','giles','gill','gillespie','gilliam','gilmore','glass','glenn','glover','goff','golden','gomez','gonzales','gonzalez','good','goodman','goodwin','gordon','gould','graham','grant','graves','gray','green','greene','greer','gregory','griffin','griffith','grimes','gross','guerra','guerrero','guthrie','gutierrez','guy','guzman','hahn','hale','haley','hall','hamilton','hammond','hampton','hancock','haney','hansen','hanson','hardin','harding','hardy','harmon','harper','harrell','harrington','harris','harrison','hart','hartman','harvey','hatfield','hawkins','hayden','hayes','haynes','hays','head','heath','hebert','henderson','hendricks','hendrix','henry','hensley','henson','herman','hernandez','herrera','herring','hess','hester','hewitt','hickman','hicks','higgins','hill','hines','hinton','hobbs','hodge','hodges','hoffman','hogan','holcomb','holden','holder','holland','holloway','holman','holmes','holt','hood','hooper','hoover','hopkins','hopper','horn','horne','horton','house','houston','howard','howe','howell','hubbard','huber','hudson','huff','huffman','hughes','hull','humphrey','hunt','hunter','hurley','hurst','hutchinson','hyde','ingram','irwin','jackson','jacobs','jacobson','james','jarvis','jefferson','jenkins','jennings','jensen','jimenez','johns','johnson','johnston','jones','jordan','joseph','joyce','joyner','juarez','justice','kane','kaufman','keith','keller','kelley','kelly','kemp','kennedy','kent','kerr','key','kidd','kim','king','kinney','kirby','kirk','kirkland','klein','kline','knapp','knight','knowles','knox','koch','kramer','lamb','lambert','lancaster','landry','lane','lang','langley','lara','larsen','larson','lawrence','lawson','le','leach','leblanc','lee','leon','leonard','lester','levine','levy','lewis','lindsay','lindsey','little','livingston','lloyd','logan','long','lopez','lott','love','lowe','lowery','lucas','luna','lynch','lynn','lyons','macdonald','macias','mack','madden','maddox','maldonado','malone','mann','manning','marks','marquez','marsh','marshall','martin','martinez','mason','massey','mathews','mathis','matthews','maxwell','may','mayer','maynard','mayo','mays','mcbride','mccall','mccarthy','mccarty','mcclain','mcclure','mcconnell','mccormick','mccoy','mccray','mccullough','mcdaniel','mcdonald','mcdowell','mcfadden','mcfarland','mcgee','mcgowan','mcguire','mcintosh','mcintyre','mckay','mckee','mckenzie','mckinney','mcknight','mclaughlin','mclean','mcleod','mcmahon','mcmillan','mcneil','mcpherson','meadows','medina','mejia','melendez','melton','mendez','mendoza','mercado','mercer','merrill','merritt','meyer','meyers','michael','middleton','miles','miller','mills','miranda','mitchell','molina','monroe','montgomery','montoya','moody','moon','mooney','moore','morales','moran','moreno','morgan','morin','morris','morrison','morrow','morse','morton','moses','mosley','moss','mueller','mullen','mullins','munoz','murphy','murray','myers','nash','navarro','neal','nelson','newman','newton','nguyen','nichols','nicholson','nielsen','nieves','nixon','noble','noel','nolan','norman','norris','norton','nunez','obrien','ochoa','oconnor','odom','odonnell','oliver','olsen','olson','oneal','oneil','oneill','orr','ortega','ortiz','osborn','osborne','owen','owens','pace','pacheco','padilla','page','palmer','park','parker','parks','parrish','parsons','pate','patel','patrick','patterson','patton','paul','payne','pearson','peck','pena','pennington','perez','perkins','perry','peters','petersen','peterson','petty','phelps','phillips','pickett','pierce','pittman','pitts','pollard','poole','pope','porter','potter','potts','powell','powers','pratt','preston','price','prince','pruitt','puckett','pugh','quinn','ramirez','ramos','ramsey','randall','randolph','rasmussen','ratliff','ray','raymond','reed','reese','reeves','reid','reilly','reyes','reynolds','rhodes','rice','rich','richard','richards','richardson','richmond','riddle','riggs','riley','rios','rivas','rivera','rivers','roach','robbins','roberson','roberts','robertson','robinson','robles','rocha','rodgers','rodriguez','rodriquez','rogers','rojas','rollins','roman','romero','rosa','rosales','rosario','rose','ross','roth','rowe','rowland','roy','ruiz','rush','russell','russo','rutledge','ryan','salas','salazar','salinas','sampson','sanchez','sanders','sandoval','sanford','santana','santiago','santos','sargent','saunders','savage','sawyer','schmidt','schneider','schroeder','schultz','schwartz','scott','sears','sellers','serrano','sexton','shaffer','shannon','sharp','sharpe','shaw','shelton','shepard','shepherd','sheppard','sherman','shields','short','silva','simmons','simon','simpson','sims','singleton','skinner','slater','sloan','small','smith','snider','snow','snyder','solis','solomon','sosa','soto','sparks','spears','spence','spencer','stafford','stanley','stanton','stark','steele','stein','stephens','stephenson','stevens','stevenson','stewart','stokes','stone','stout','strickland','strong','stuart','suarez','sullivan','summers','sutton','swanson','sweeney','sweet','sykes','talley','tanner','tate','taylor','terrell','terry','thomas','thompson','thornton','tillman','todd','torres','townsend','tran','travis','trevino','trujillo','tucker','turner','tyler','tyson','underwood','valdez','valencia','valentine','valenzuela','vance','vang','vargas','vasquez','vaughan','vaughn','vazquez','vega','velasquez','velazquez','velez','villarreal','vincent','vinson','wade','wagner','walker','wall','wallace','waller','walls','walsh','walter','walters','walton','ward','ware','warner','warren','washington','waters','watkins','watson','watts','weaver','webb','weber','webster','weeks','weiss','welch','wells','west','wheeler','whitaker','white','whitehead','whitfield','whitley','whitney','wiggins','wilcox','wilder','wiley','wilkerson','wilkins','wilkinson','william','williams','williamson','willis','wilson','winters','wise','witt','wolf','wolfe','wong','wood','woodard','woods','woodward','wooten','workman','wright','wyatt','wynn','yang','yates','york','young','zamora','zimmerman');
        $count = number::where('network', 'textnow')->where('network_login', 'not like', 'aa@%')->count();
        $count_free = number::where('network', 'textnow')->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('last_checked', '>', Carbon::now()->subDays(30)->toDateTimeString())->count();
        $chars = "abcdefghijklmnKLMNOPQRSTUVWXYZ0123456789-_@?!";

        //$password =  substr(str_shuffle($chars),0,8);
        $new_email = $last_names[array_rand($last_names)] . rand(0,999) ."@premiumbooks.net";

            return view('numbersadmin.addTextnow')->with('email', $email)->with('password', $password)->with('new_email', $new_email)->with('count_free', $count_free)->with('count', $count)->with('id', $id);

        }else{
            return redirect('/login');
        }

    }

    public function newtextnow(){

        if ($this->isNumbersAdmin()){
            return view('numbersadmin.addCombo');
        }
        
    }

    
    public function addCombo(){


        $combo_array = preg_split("/\r\n|\n|\r/", Input::get('combo')); 

        foreach ($combo_array as $combo) {
            $entry = explode(":", $combo);
            $number = new number();
    
            $number->number = $entry[2];
            $number->network_login = $entry[0];
            $number->network_password = $entry[1];
            $number->network = "textnow";
            $number->last_checked =  Carbon::now()->subDays(7000);
            $number->save();


        }

        $added = count($combo_array);
        flash()->overlay($added . ' Numbers added!', '+' . $added);
    
        return back();

        


    }

    public function numbersadmin(){
        if ($this->isNumbersAdmin()){
        return redirect('/numbersadmin/addtextnow');
        }
    }




    public function isDemoNeedUpdate(){

        $Simplepush = new Simplepush;
        $count_free = number::where('info', null)->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('is_active', true)->where('last_checked', '>', Carbon::now()->subDays(5)->toDateTimeString())->count();
   
     
        
        $Simplepush->send("W6T4J9", "Available numbers", "Available numbers count: " . $count_free, "Available numbers");


        $demoNumbers = number::all()->where('is_private',false)->where('is_active',true)->sortBydesc('last_checked');
     
        foreach ($demoNumbers as $demoNumber) {
            echo $demoNumber['number'] . "<br>";
            if ($demoNumber['last_checked'] < Carbon::now()->subMinutes(330)){

                $count_free = number::where('info', null)->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('is_active', true)->where('last_checked', '>', Carbon::now()->subDays(5)->toDateTimeString())->count();
 
                if ($count_free > 1){
       
                    

                    $newNumber = number::where('info', null)->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('is_active', true)->where('last_checked', '>', Carbon::now()->subDays(5)->toDateTimeString())->first();
                    echo $newNumber['number'] . "<br>";
            
                    $expiration = Carbon::now()->addMonth(20)->addDays(10);  
                        number::where('id', '=', $newNumber['id'])->update(['is_private' => false]);
                        number::where('id', '=', $newNumber['id'])->update(['expiration' => $expiration]);
                        message::where('receiver', $newNumber['number'])->delete();
                        number::where('id', $demoNumber['id'])->delete();
                        $Simplepush->send("W6T4J9", "Demo number updated", "Old: " . $demoNumber['number'] . "- New: " . $newNumber['number'] . " - Avalable: " .  $count_free, "Demo number updated");
                }else{
                    $Simplepush->send("W6T4J9", "Update demo number problem", "Old: " . $demoNumber['number'] . "- last inbox: " . $demoNumber['last_checked'] , "No numbers in database warning");
                }
            }
        }
    }


    public function DemoNumbersUpdate(){

        $Simplepush = new Simplepush;
        $count_free = number::where('info', null)->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('is_active', true)->where('last_checked', '>', Carbon::now()->subDays(5)->toDateTimeString())->count();
   
     
        
        $Simplepush->send("W6T4J9", "Available numbers", "Available numbers count: " . $count_free, "Available numbers");


        $demoNumbers = number::all()->where('is_private',false)->where('is_active',true)->sortBydesc('last_checked');
     
        foreach ($demoNumbers as $demoNumber) {
            echo $demoNumber['number'] . "<br>";

                $count_free = number::where('info', null)->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('is_active', true)->where('last_checked', '>', Carbon::now()->subDays(5)->toDateTimeString())->count();
 
                if ($count_free > 1){
                    $newNumber = number::where('info', null)->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('is_active', true)->where('last_checked', '>', Carbon::now()->subDays(5)->toDateTimeString())->first();
                    $count_free = $count_free -1;
                    echo $newNumber['number'] . "<br>";
            
                    $expiration = Carbon::now()->addMonth(20)->addDays(10);  
                        number::where('id', '=', $newNumber['id'])->update(['is_private' => false]);
                        number::where('id', '=', $newNumber['id'])->update(['expiration' => $expiration]);
                        message::where('receiver', $newNumber['number'])->delete();
                        number::where('id', $demoNumber['id'])->delete();
                        $Simplepush->send("W6T4J9", "Demo number updated", "Old: " . $demoNumber['number'] . "- New: " . $newNumber['number'] . " - Avalable: " .  $count_free, "Demo number updated");
                //send an email to subscribers here
                }else{
                    $Simplepush->send("W6T4J9", "Update demo number problem", "Old: " . $demoNumber['number'] . "- last inbox: " . $demoNumber['last_checked'] , "No numbers in database warning");
                }
            
        }
    }
        

    public function showSources(){

        if (Input::get('type')){
            $type = Input::get('type');
            $period = Input::get('period');
        }else{
            return view('admin.sources');

        }

        switch ($period){
            case "24h":
                $startDate = Carbon::now()->subDay();
                break;
            case "7 Days":
                $startDate = Carbon::now()->subDays(7);
                break;
            case "1 Month":
                $startDate = Carbon::now()->subMonth();
                break;
            case "3 Months":
                $startDate = Carbon::now()->subMonths(3);
                break;
            case "All":
                $startDate = Carbon::now()->subYears(20);
                break;
        }


        switch ($type){
            case "Topups":
                $chart = Charts::database(paymentlog::all()->where('status',"Completed")->where('created_at', '>', $startDate)->sortByDesc('id'), 'pie', 'highcharts')
                    ->title("Topup Sources: " . $period)
                    ->elementLabel("Total")
                    ->aggregateColumn('source','sum')
                    ->dimensions(0, 400)
                    ->groupBy('source');

                break;
            case "Subscribes":
                $chart = Charts::database(subscriber::all()->where('created_at', '>', $startDate)->sortByDesc('id'), 'pie', 'highcharts')
                    ->title("Subscribe Sources: " . $period)
                    ->elementLabel("Total")
                    ->aggregateColumn('source','sum')
                    ->dimensions(0, 400)
                    ->groupBy('source');
                break;
            case "Registrations":
                $chart = Charts::database(user::all()->where('created_at', '>', $startDate)->sortByDesc('id'), 'pie', 'highcharts')
                    ->title("Registration Sources: " . $period)
                    ->elementLabel("Total")
                    ->aggregateColumn('source','sum')
                    ->dimensions(0, 400)
                    ->groupBy('source');
                break;
            case "Renews":
                break;
            case "Spending":
                break;
        }



        return view('admin.chart')->with('chart',$chart);
    }




    public function showOrders(){
        $records = number::all();
        $columns =  array("id", "number", "country", "expiration", "is_private", "network", "network_login", "network_password", "email");
        $data = $this->formatData($records,$columns);
        return view('admin.show')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }

    Public function support(){


        $records = contact::all()->where('is_responded',false)->where('is_support',true)->sortByDesc('id');
        $columns =  array("id", "is_support", "created_at", "subject", "name", "email","message");

		//$info = $this->getUserInfo($support['email']);
		
		
        $data = $this->formatData($records,$columns);
		
		
        return view('admin.support')->with('rows', $data['rows'])->with('columns', $data['columns']);

    }    
	
	Public function contact(){


        $records = contact::all()->where('is_responded',false)->where('is_support',false)->sortByDesc('id');
        $columns =  array("id", "is_support", "created_at", "subject", "name", "email","message");

        $data = $this->formatData($records,$columns);
        return view('admin.support')->with('rows', $data['rows'])->with('columns', $data['columns']);

    }


    public function mailer(){
        return view("admin.mailer");
    }
    public function flatMailer(){
        return view("admin.flatMailer");
    }

    public function preview($text1, $text2, $text3, $text4, $heading1, $heading2, $heading3, $heading4, $img1, $img2, $button1, $button2, $button3,$buttonURL1, $buttonURL2, $buttonURL3){

        $heading1 =  urldecode ($heading1);
        $heading2 =  urldecode ($heading2);
        $heading3 =  urldecode ($heading3);
        $heading4 =  urldecode ($heading4);

        $text2 =  urldecode ($text2);
        $text1 =  urldecode($text1);
        $text3 =  urldecode($text3);
        $text4 =  urldecode($text4);
        $button1 =  urldecode($button1);
        $buttonURL1 =  base64_decode($buttonURL1);
        $button2 =  urldecode($button2);
        $buttonURL2 =  base64_decode($buttonURL2);
        $button3 =  urldecode($button3);
        $buttonURL3 =  base64_decode($buttonURL3);
        $img1 =  base64_decode($img1);
        $img2 =  base64_decode($img2);

        if ($heading1 == "nothing"){$heading1 = null;}
        if ($heading2 == "nothing"){$heading2 = null;}
        if ($text2 == "nothing"){$text2 = null;}
        if ($text1 == "nothing"){$text1 = null;}

        if ($heading3 == "nothing"){$heading3 = null;}
        if ($heading4 == "nothing"){$heading4 = null;}
        if ($text3 == "nothing"){$text3 = null;}
        if ($text4 == "nothing"){$text4 = null;}


        if ($button1 == "nothing"){$button1 = null;}
        if ($buttonURL1 == "nothing"){$buttonURL1 = null;}
        if ($button2 == "nothing"){$button2 = null;}
        if ($buttonURL2 == "nothing"){$buttonURL2 = null;}
        if ($button3 == "nothing"){$button3 = null;}
        if ($buttonURL3 == "nothing"){$buttonURL3 = null;}

        if ($img1 == "nothing"){$img1 = null;}
        if ($img2 == "nothing"){$img2 = null;}

        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.generic',
            [
                'email' => "no-reply@receive-sms.com",
                'img1' => $img1,
                'img2' => $img2,
                'button1' => $button1,
                'button2' => $button2,
                'button3' => $button3,
                'text1' => $text1,
                'heading1' => $heading1,
                'heading2' => $heading2,
                'text3' => $text3,
                'text4' => $text4,
                'heading3' => $heading3,
                'heading4' => $heading4,
                'text2' => $text2,
                'buttonURL1' => $buttonURL1,
                'buttonURL2' => $buttonURL2,
                'buttonURL3' => $buttonURL3
            ]

        );
    }

    public function coupon(){

        $records = coupon::all()->where('is_active',"=",true)->where('expiration', '>', Carbon::now());
        $columns =  array("id", "code", "minimum_price", "paymentsystem_id", "value", "expiration");

        $data = $this->formatData($records, $columns);
        return View('admin.coupon')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }


    public function give(){
        return view('admin.give');
    }

    
    public function addNumbers(){
        return view('admin.addNumber');
    }

    
    public function updatenumber($id, $num, $user, $pwd, $ret, $ret2){
        echo $id . "<br/>" . $num . "<br/>" . $user . "<br/>" . $pwd . "<br/>" . $ret . "<br/>" . $ret2;

        if ($ret2 == $user){
        //if (strpos($ret, "successfully") !== false and $ret2 == $user){
            number::where('id', '=', $id)->update(['network_login' => $user]);
            number::where('id', '=', $id)->update(['network_password' =>  $pwd]);
            number::where('id', '=', $id)->update(['info' => 'User name updated']);
            //number::where('id', '=', $id)->update(['last_checked' =>  carbon::now()]);

            str_replace("%20", "", $num);
            $num = preg_replace('/[^0-9]/', '', $num);
            

            if (is_numeric($num)){
                if ($num[0] <> "1"){$num = "1" . $num;}
                number::where('number', $num)->where('email', null)->delete();
                $number = new number();
                $number->number = $num;
                $number->network_login = $user;
                $number->network_password = $pwd;
                $number->network = "textnow";
                $number->last_checked = carbon::now();
                $number->save();
                number::where('id', '=', $id)->update(['network_login' => "aa@expired.com"]);
                return "<br/><br/>replaced: " . $num;
            }else{
                $new_date = Carbon::now()->subDays(2)->toDateTimeString();
                number::where('id', '=', $id)->update(['last_checked' => $new_date]);
                //return redirect('/admin/updatenumbersmacro');
                return "";
        
            }       

        }
        

    }

    public function doAddNumber(){

        $num = Input::get('number');
        $id = Input::get('id');
        str_replace("%20", "", $num);
        $num = preg_replace('/[^0-9]/', '', $num);
        if ($num[0] <> "1"){$num = "1" . $num;}

        number::where('number', $num)->where('email', null)->delete();
        $number = new number();

        $number->number = $num;
        $number->network_login = Input::get('user');
        $number->network_password = Input::get('pwd');
        $number->network = Input::get('network');
        if (Input::get('set_as_checked') == true){
            $number->last_checked = carbon::now();
        }
        
        $number->save();

        number::where('id', '=', $id)->update(['network_login' => "aa@expired.com"]);

        flash()->overlay('Number: ' . $num . ' Added!', '+1');

        return back();

    }

    public function addCoupon(){

        $coupon = new coupon();

        $coupon->code = Input::get('code');
        $coupon->minimum_price = Input::get('minimum_price');
        $coupon->paymentsystem_id = Input::get('paymentsystem_id');
        $coupon->value = Input::get('value');
        $coupon->expiration = carbon::parse(Input::get('expiration'));
        $coupon->save();

        flash()->overlay('Coupon Saved ;)', 'Nice!');

        return $this->coupon();

    }

    public  function gIP($ip){

    
      

        $result = DB::select( DB::raw("SELECT b.country FROM ip2nationCountries b,
        ip2nation i 
    WHERE 
        i.ip < INET_ATON('$ip') 
        AND 
        b.code = i.country 
    ORDER BY 
        i.ip DESC 
    LIMIT 0,1") );

$arr = [];
foreach($result as $row)
{
    $arr[] = (array) $row;
}

return $arr[0]["country"];

    }
    public function test(){

        //$Simplepush = new Simplepush;

        //return $Simplepush->send("W6T4J9", "test title", "test 1", "test2");
        $ip = gethostbyname("google.com");
$url = "http://" . $domain;
$orignal_parse = parse_url($url, PHP_URL_HOST);
$get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
$read = stream_socket_client("ssl://" . $orignal_parse . ":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
$cert = stream_context_get_params($read);
$result = (!is_null($cert)) ? true : false;

            return $result;


        
    }

    public function freeNumber($email,$days = 31){
        $number = number::all()->where('is_private',true)->where('is_active',true)->where('email', null)->sortBydesc('last_checked')->first();
        if ($number['number']){
            $expiration = Carbon::now()->addDays($days);
            number::where('id', '=', $number['id'])->update(['email' => $email]);
            number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);
            message::where('receiver', $number['number'])->delete();
            echo $this->formatPhoneNumber($number['number']);
            return $this->formatPhoneNumber($number['number']);
        }else{
            return false;
        }



    }

    function formatPhoneNumber($phoneNumber) {
        $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

        if(strlen($phoneNumber) > 10) {
            $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
            $areaCode = substr($phoneNumber, -10, 3);
            $nextThree = substr($phoneNumber, -7, 3);
            $lastFour = substr($phoneNumber, -4, 4);

            $phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
        }
        else if(strlen($phoneNumber) == 10) {
            $areaCode = substr($phoneNumber, 0, 3);
            $nextThree = substr($phoneNumber, 3, 3);
            $lastFour = substr($phoneNumber, 6, 4);

            $phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
        }
        else if(strlen($phoneNumber) == 7) {
            $nextThree = substr($phoneNumber, 0, 3);
            $lastFour = substr($phoneNumber, 3, 4);

            $phoneNumber = $nextThree.'-'.$lastFour;
        }

        return $phoneNumber;
    }
    public function giveNumbers(){

        $amount = Input::get('amount');
        $email = Input::get('user_email');
    
        $user = user::where('email',$email)->first();
        $name = $user['name'];
   
        $numbers = number::all()->where('is_private',true)->where('network_login','<>', 'aa@expired.com')->where('is_active',true)->where('email', null)->sortBydesc('last_checked')->take($amount);
     
        $expiration = Carbon::now()->addMonth(1)->addDays(10)->toDateTimeString();

        $data['numbers'] = array();
        foreach ($numbers as $number) {
            $number = number::where('id', '=', $number['id'])->first();
            number::where('id', '=', $number['id'])->update(['email' => $email]);
            number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);
            message::where('receiver', $number['number'])->delete();
            $addedNumber = array($number['number'],$number['country'],"International",$expiration);
            array_push($data['numbers'],$addedNumber);
        }

        $data['name'] = $name;
        Mail::to($email)->queue(new numbersReady($data));

        flash()->overlay("You successfully added $amount numbers to " . $name .  "'s account! (" . $email . ").", 'Good');

        return $this->give();

    }


    public function giveNumbersSupport($id){


        $record = contact::all()->where('id',$id)->first();


        $email = $record->email;
        $data['subject'] = "Re: " .$record->subject;
        $data['message']= "I will add a different number to your account. If you still have a problem please use the support form to get fast answer.";
        $data['name']= $record->name;


        Mail::to($email)->queue(new response($data));

        $record->is_responded = true;
        $record->save();


        $name = $data['name'];
        $number = number::all()->where('is_private',true)->where('is_active',true)->where('email', null)->sortBydesc('last_checked')->first();
        $expiration = Carbon::now()->addMonth(1)->addDays(10);

        $data2['numbers'] = array();
      
            $number = number::where('id', '=', $number['id'])->first();
            number::where('id', '=', $number['id'])->update(['email' => $email]);
            number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);
            message::where('receiver', $number['number'])->delete();
            $addedNumber = array($number['number'],$number['country'],"International",$expiration);
            array_push($data2['numbers'],$addedNumber);
        

        $data2['name'] = $name;
        Mail::to($email)->queue(new numbersReady($data2));

      

        return $data2['numbers'];
    }


    public function dataFix (){

        $users = user::all()->where('password', null);

        foreach ($users as $user) {

            if ($user['flat_password'] == null or $user['flat_password'] == "" or $user['flat_password'] == "1"){
                $user->update(['flat_password' => "ef5f5zz5x"]);
            }

            if ($user['password'] == null or $user['password'] == ""){

                $password = bcrypt($user['flat_password']);
                $user->update(['password' => $password]);
            }

            if ($user['name'] == null or $user['name'] == "" or $user['name'] == "n"){
                $split = explode("@", $user['email']);

                $name = $split[0];
                $user->update(['name' => $name]);

            }




        }


    }

    public function deleteEmail($id){
        $record = contact::where('id',$id)->get()->first();
        $record->delete();
        return $this->support();

    }
    public function sendResponse(){


        $id = Input::get('id');
        $email = Input::get('email');
        $data['subject'] = "Re: " . Input::get('subject');
        $data['message']= Input::get('response');
        $data['name']= Input::get('name');

        Mail::to($email)->queue(new response($data));

        $record = contact::all()->where('id',$id)->first();
        $record->is_responded = true;
        $record->save();



        return back();
    }

    public function send(){





        $type =  Input::get('list');
        $emails = $this->generateEmailList($type);




        flash()->overlay("Good Luck with the $$$", 'Good luck');
        return view("admin.mailer");

    }


    private function generateEmailList($type){
        switch ($type){
            case "All Subscribers and Users":
                return array("abdelilah.sbaai@gmail.com", "mrchioua@gmail.com");
            case "All Subscribers":
            case "All Users":
            case "Subscribers Didn't register":
            case "Users Topped Up":
            case "Users Didn't Top Up":
            case "Users With Numbers":
            case "Users Without Numbers":
    }
    return "";
    }

    private function nicetime($date)
{
    if(empty($date)) {
        return "No date provided";
    }

    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
    date_default_timezone_set("UTC");
    $now             = time();
    $unix_date         = strtotime($date);

    if(empty($unix_date)) {
        return "Bad date";
    }
    if($now > $unix_date) {
        $difference     = $now - $unix_date;
        $tense         = "ago";

    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if($difference != 1) {
        $periods[$j].= "s";
    }

    return "$difference $periods[$j] {$tense}";
}
public function indexMacro($lines){
        macro::truncate();
        
        foreach ($lines as $line) {
            $macro = new macro();
            $macro->line = $line;
            $macro->save();
        }
        
}
public function getPayPalPass(){
   


}
public function VerifyMacro(){
    $messagesControllerObject = new messagesController();
    $testSMSArray = $messagesControllerObject->SmsForTest();
    $MaxSMS = 27;
    $macro = array();


    $numbers = number::all()
    ->where('network','textnow')
    ->where('is_private',true)
    ->where('is_active',true)
    ->where('last_checked', '<=', Carbon::now()->subDays(3)->toDateTimeString())
    ->sortBy('last_checked')
    ->pluck('number')
    ->toArray();

    $neededAccs = (count($numbers) / $MaxSMS) + 1;

    $logins = number::all()
    ->where('network','textnow')
    ->where('is_active',true)
    ->sortByDesc('last_checked')
    ->pluck('network_password', 'network_login')
    ->take($neededAccs)
    ->toArray();


    $i = 0;
    foreach ($logins as $username=>$password) {
        // login
        array_push($macro, 'WAIT SECONDS=3'); 
        array_push($macro, 'URL GOTO=https://www.textnow.com/logout'); 
        array_push($macro, 'WAIT SECONDS=5'); 
        array_push($macro, 'URL GOTO=https://www.textnow.com/login');
        array_push($macro, 'WAIT SECONDS=5');  
        array_push($macro, 'EVENT TYPE=MOUSEDOWN SELECTOR="#txt-username" BUTTON=0'); 
        array_push($macro, 'EVENT TYPE=MOUSEMOVE SELECTOR="#txt-username" POINT="(294,196)"'); 
        array_push($macro, 'EVENT TYPE=MOUSEUP POINT="(294,196)"'); 
        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#txt-username" BUTTON=0'); 
        array_push($macro, 'EVENTS TYPE=KEYPRESS SELECTOR="#txt-username" CHARS="'. $username . '"'); 
        array_push($macro, 'WAIT SECONDS=3'); 
        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#txt-password" BUTTON=0'); 
        array_push($macro, 'EVENTS TYPE=KEYPRESS SELECTOR="#txt-password" CHARS="'. $password . '"'); 
        array_push($macro, 'WAIT SECONDS=2'); 
        array_push($macro, 'SET !ENCRYPTION NO'); 
        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#btn-login" BUTTON=0'); 
        //array_push($macro, 'SET !EXTRACT NULL'); 
        //array_push($macro, 'TAG POS=1 TYPE=span ATTR=CLASS:uikit-text<sp>uikit-text--micro<sp>uikit-text--danger&&TXT:* EXTRACT=TXT'); 
        //array_push($macro, 'PROMPT {{!EXTRACT}}'); 
     

      $start = $i * $MaxSMS;
      $end = $start + $MaxSMS - 1;
    for ($x = $start; $x <= $end; $x++) {
        if (array_key_exists($x, $numbers)){
            // send sms to this number
            $smsmessage = $testSMSArray[array_rand($testSMSArray)];
            array_push($macro, 'WAIT SECONDS=4'); 
            array_push($macro, 'EVENT TYPE=MOUSEDOWN SELECTOR="#newText" BUTTON=0'); 
            array_push($macro, 'EVENT TYPE=MOUSEMOVE SELECTOR="#newText" POINT="(360,37)"'); 
            array_push($macro, 'EVENT TYPE=MOUSEUP POINT="(360,37)"'); 
            array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#newText" BUTTON=0'); 
            array_push($macro, 'EVENT TYPE=KEYPRESS SELECTOR="#recipientsView>DIV>DIV>INPUT" KEY=17'); 
            array_push($macro, 'WAIT SECONDS=2'); 
            array_push($macro, 'EVENTS TYPE=KEYPRESS SELECTOR="#text-input" CHARS="'. $numbers[$x] . '"'); 
            array_push($macro, 'WAIT SECONDS=2'); 
            array_push($macro, 'EVENT TYPE=CLICK SELECTOR="HTML>BODY>DIV:nth-of-type(2)>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV:nth-of-type(2)>DIV>DIV>DIV:nth-of-type(3)>DIV>DIV>DIV>DIV>FORM>DIV:nth-of-type(3)" BUTTON=0'); 
            array_push($macro, 'EVENT TYPE=CLICK SELECTOR="HTML>BODY>DIV:nth-of-type(2)>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV:nth-of-type(2)>DIV>DIV>DIV:nth-of-type(3)>DIV>DIV>DIV>DIV>FORM>DIV:nth-of-type(4)>TEXTAREA" BUTTON=0'); 
            array_push($macro, 'WAIT SECONDS=4'); 
            array_push($macro, 'EVENTS TYPE=KEYPRESS SELECTOR="HTML>BODY>DIV:nth-of-type(2)>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV:nth-of-type(2)>DIV>DIV>DIV:nth-of-type(3)>DIV>DIV>DIV>DIV>FORM>DIV:nth-of-type(4)>TEXTAREA" CHARS="'. $smsmessage . '"'); 
            array_push($macro, 'EVENT TYPE=CLICK SELECTOR="HTML>BODY>DIV:nth-of-type(2)>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV:nth-of-type(2)>DIV>DIV>DIV:nth-of-type(3)>DIV>DIV>DIV>DIV>FORM>DIV:nth-of-type(3)" BUTTON=0');   
        }
        
    } 
      $i = $i + 1;
    }

    $this->indexMacro($macro);
    return $this->runMacro(true);


}

public function testMacro(){
    $macro = array();

    $i = 0;

    for ($x = 0; $x <= 150; $x++) {

            array_push($macro, 'URL GOTO=https://www.receive-sms.com/search?q=' . $i); 
           
        $i = $i + 1;
    } 
      


    $this->indexMacro($macro);
    return $this->runMacro(true);
    

}


public function updateNumbersMacro($stage="login",$id=null,$ret=null, $fix1=null, $fix2=null, $fix3=null){
    $myPassword = "PP@123456";
    $number = number::where('network_login', 'not like', 'aa@%')->where('network', 'textnow')->get()->sortBy('last_checked')->first();


    $last_names = array('abbott','acevedo','acosta','adams','adkins','aguilar','aguirre','albert','alexander','alford','allen','allison','alston','alvarado','alvarez','anderson','andrews','anthony','armstrong','arnold','ashley','atkins','atkinson','austin','avery','avila','ayala','ayers','bailey','baird','baker','baldwin','ball','ballard','banks','barber','barker','barlow','barnes','barnett','barr','barrera','barrett','barron','barry','bartlett','barton','bass','bates','battle','bauer','baxter','beach','bean','beard','beasley','beck','becker','bell','bender','benjamin','bennett','benson','bentley','benton','berg','berger','bernard','berry','best','bird','bishop','black','blackburn','blackwell','blair','blake','blanchard','blankenship','blevins','bolton','bond','bonner','booker','boone','booth','bowen','bowers','bowman','boyd','boyer','boyle','bradford','bradley','bradshaw','brady','branch','bray','brennan','brewer','bridges','briggs','bright','britt','brock','brooks','brown','browning','bruce','bryan','bryant','buchanan','buck','buckley','buckner','bullock','burch','burgess','burke','burks','burnett','burns','burris','burt','burton','bush','butler','byers','byrd','cabrera','cain','calderon','caldwell','calhoun','callahan','camacho','cameron','campbell','campos','cannon','cantrell','cantu','cardenas','carey','carlson','carney','carpenter','carr','carrillo','carroll','carson','carter','carver','case','casey','cash','castaneda','castillo','castro','cervantes','chambers','chan','chandler','chaney','chang','chapman','charles','chase','chavez','chen','cherry','christensen','christian','church','clark','clarke','clay','clayton','clements','clemons','cleveland','cline','cobb','cochran','coffey','cohen','cole','coleman','collier','collins','colon','combs','compton','conley','conner','conrad','contreras','conway','cook','cooke','cooley','cooper','copeland','cortez','cote','cotton','cox','craft','craig','crane','crawford','crosby','cross','cruz','cummings','cunningham','curry','curtis','dale','dalton','daniel','daniels','daugherty','davenport','david','davidson','davis','dawson','day','dean','decker','dejesus','delacruz','delaney','deleon','delgado','dennis','diaz','dickerson','dickson','dillard','dillon','dixon','dodson','dominguez','donaldson','donovan','dorsey','dotson','douglas','downs','doyle','drake','dudley','duffy','duke','duncan','dunlap','dunn','duran','durham','dyer','eaton','edwards','elliott','ellis','ellison','emerson','england','english','erickson','espinoza','estes','estrada','evans','everett','ewing','farley','farmer','farrell','faulkner','ferguson','fernandez','ferrell','fields','figueroa','finch','finley','fischer','fisher','fitzgerald','fitzpatrick','fleming','fletcher','flores','flowers','floyd','flynn','foley','forbes','ford','foreman','foster','fowler','fox','francis','franco','frank','franklin','franks','frazier','frederick','freeman','french','frost','fry','frye','fuentes','fuller','fulton','gaines','gallagher','gallegos','galloway','gamble','garcia','gardner','garner','garrett','garrison','garza','gates','gay','gentry','george','gibbs','gibson','gilbert','giles','gill','gillespie','gilliam','gilmore','glass','glenn','glover','goff','golden','gomez','gonzales','gonzalez','good','goodman','goodwin','gordon','gould','graham','grant','graves','gray','green','greene','greer','gregory','griffin','griffith','grimes','gross','guerra','guerrero','guthrie','gutierrez','guy','guzman','hahn','hale','haley','hall','hamilton','hammond','hampton','hancock','haney','hansen','hanson','hardin','harding','hardy','harmon','harper','harrell','harrington','harris','harrison','hart','hartman','harvey','hatfield','hawkins','hayden','hayes','haynes','hays','head','heath','hebert','henderson','hendricks','hendrix','henry','hensley','henson','herman','hernandez','herrera','herring','hess','hester','hewitt','hickman','hicks','higgins','hill','hines','hinton','hobbs','hodge','hodges','hoffman','hogan','holcomb','holden','holder','holland','holloway','holman','holmes','holt','hood','hooper','hoover','hopkins','hopper','horn','horne','horton','house','houston','howard','howe','howell','hubbard','huber','hudson','huff','huffman','hughes','hull','humphrey','hunt','hunter','hurley','hurst','hutchinson','hyde','ingram','irwin','jackson','jacobs','jacobson','james','jarvis','jefferson','jenkins','jennings','jensen','jimenez','johns','johnson','johnston','jones','jordan','joseph','joyce','joyner','juarez','justice','kane','kaufman','keith','keller','kelley','kelly','kemp','kennedy','kent','kerr','key','kidd','kim','king','kinney','kirby','kirk','kirkland','klein','kline','knapp','knight','knowles','knox','koch','kramer','lamb','lambert','lancaster','landry','lane','lang','langley','lara','larsen','larson','lawrence','lawson','le','leach','leblanc','lee','leon','leonard','lester','levine','levy','lewis','lindsay','lindsey','little','livingston','lloyd','logan','long','lopez','lott','love','lowe','lowery','lucas','luna','lynch','lynn','lyons','macdonald','macias','mack','madden','maddox','maldonado','malone','mann','manning','marks','marquez','marsh','marshall','martin','martinez','mason','massey','mathews','mathis','matthews','maxwell','may','mayer','maynard','mayo','mays','mcbride','mccall','mccarthy','mccarty','mcclain','mcclure','mcconnell','mccormick','mccoy','mccray','mccullough','mcdaniel','mcdonald','mcdowell','mcfadden','mcfarland','mcgee','mcgowan','mcguire','mcintosh','mcintyre','mckay','mckee','mckenzie','mckinney','mcknight','mclaughlin','mclean','mcleod','mcmahon','mcmillan','mcneil','mcpherson','meadows','medina','mejia','melendez','melton','mendez','mendoza','mercado','mercer','merrill','merritt','meyer','meyers','michael','middleton','miles','miller','mills','miranda','mitchell','molina','monroe','montgomery','montoya','moody','moon','mooney','moore','morales','moran','moreno','morgan','morin','morris','morrison','morrow','morse','morton','moses','mosley','moss','mueller','mullen','mullins','munoz','murphy','murray','myers','nash','navarro','neal','nelson','newman','newton','nguyen','nichols','nicholson','nielsen','nieves','nixon','noble','noel','nolan','norman','norris','norton','nunez','obrien','ochoa','oconnor','odom','odonnell','oliver','olsen','olson','oneal','oneil','oneill','orr','ortega','ortiz','osborn','osborne','owen','owens','pace','pacheco','padilla','page','palmer','park','parker','parks','parrish','parsons','pate','patel','patrick','patterson','patton','paul','payne','pearson','peck','pena','pennington','perez','perkins','perry','peters','petersen','peterson','petty','phelps','phillips','pickett','pierce','pittman','pitts','pollard','poole','pope','porter','potter','potts','powell','powers','pratt','preston','price','prince','pruitt','puckett','pugh','quinn','ramirez','ramos','ramsey','randall','randolph','rasmussen','ratliff','ray','raymond','reed','reese','reeves','reid','reilly','reyes','reynolds','rhodes','rice','rich','richard','richards','richardson','richmond','riddle','riggs','riley','rios','rivas','rivera','rivers','roach','robbins','roberson','roberts','robertson','robinson','robles','rocha','rodgers','rodriguez','rodriquez','rogers','rojas','rollins','roman','romero','rosa','rosales','rosario','rose','ross','roth','rowe','rowland','roy','ruiz','rush','russell','russo','rutledge','ryan','salas','salazar','salinas','sampson','sanchez','sanders','sandoval','sanford','santana','santiago','santos','sargent','saunders','savage','sawyer','schmidt','schneider','schroeder','schultz','schwartz','scott','sears','sellers','serrano','sexton','shaffer','shannon','sharp','sharpe','shaw','shelton','shepard','shepherd','sheppard','sherman','shields','short','silva','simmons','simon','simpson','sims','singleton','skinner','slater','sloan','small','smith','snider','snow','snyder','solis','solomon','sosa','soto','sparks','spears','spence','spencer','stafford','stanley','stanton','stark','steele','stein','stephens','stephenson','stevens','stevenson','stewart','stokes','stone','stout','strickland','strong','stuart','suarez','sullivan','summers','sutton','swanson','sweeney','sweet','sykes','talley','tanner','tate','taylor','terrell','terry','thomas','thompson','thornton','tillman','todd','torres','townsend','tran','travis','trevino','trujillo','tucker','turner','tyler','tyson','underwood','valdez','valencia','valentine','valenzuela','vance','vang','vargas','vasquez','vaughan','vaughn','vazquez','vega','velasquez','velazquez','velez','villarreal','vincent','vinson','wade','wagner','walker','wall','wallace','waller','walls','walsh','walter','walters','walton','ward','ware','warner','warren','washington','waters','watkins','watson','watts','weaver','webb','weber','webster','weeks','weiss','welch','wells','west','wheeler','whitaker','white','whitehead','whitfield','whitley','whitney','wiggins','wilcox','wilder','wiley','wilkerson','wilkins','wilkinson','william','williams','williamson','willis','wilson','winters','wise','witt','wolf','wolfe','wong','wood','woodard','woods','woodward','wooten','workman','wright','wyatt','wynn','yang','yates','york','young','zamora','zimmerman');
    $chars = "abcdefghijklmnKLMNOPQRSTUVWXYZ0123456789-_@?!";

    $username =  $number["network_login"];
    $password =  $number["network_password"];
    $numberid =  $number["id"];
    $new_email = $last_names[array_rand($last_names)] . rand(0,999) ."@premiumbooks.net";
    $first_name = $last_names[array_rand($last_names)];
    $last_name = $last_names[array_rand($last_names)];
    $valfix = "{{!EXTRACT}}";



    $macro = array();

    $i = 0;

    
    if ($stage == "login" and $id == null){
    


        array_push($macro, 'URL GOTO=https://www.textnow.com/login');
        array_push($macro, 'WAIT SECONDS=3');  
        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#txt-username" BUTTON=0'); 
        array_push($macro, 'EVENTS TYPE=KEYPRESS SELECTOR="#txt-username" CHARS="'. $username . '"'); 
        array_push($macro, 'WAIT SECONDS=3'); 
        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#txt-password" BUTTON=0'); 
        array_push($macro, 'EVENTS TYPE=KEYPRESS SELECTOR="#txt-password" CHARS="'. $password . '"'); 
        array_push($macro, 'WAIT SECONDS=2'); 
        array_push($macro, 'SET !ENCRYPTION NO'); 
        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#btn-login" BUTTON=0'); 
        array_push($macro, 'WAIT SECONDS=20');
        //array_push($macro, 'URL GOTO=https://www.textnow.com/messaging');
        //array_push($macro, 'WAIT SECONDS=8');
        array_push($macro, 'SET !VAR1 {{!URLCURRENT}}');
        array_push($macro, 'SET !VAR1 EVAL("\"{{!VAR1}}\".split(\"/\").join(\"\");")');
        array_push($macro, 'TAG POS=1 TYPE=SPAN ATTR=CLASS:*uikit-text--danger EXTRACT=txt');
        array_push($macro, 'SET !EXTRACT EVAL("\"{{!EXTRACT}}\".replace(\"#EANF#\", \"no\");")');

        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="HTML>BODY>DIV:nth-of-type(5)>DIV>DIV>DIV:nth-of-type(2)>DIV>DIV>DIV>FORM>DIV>INPUT" BUTTON=0');
        array_push($macro, 'TAG POS=1 TYPE=INPUT:TEXT FORM=ID:enterAreaCodeForm ATTR=* CONTENT=' . mt_rand(200,800));
        array_push($macro, 'EVENT TYPE=KEYPRESS SELECTOR="#enterAreaCodeForm>DIV>INPUT" KEY=39');
        array_push($macro, 'TAG POS=1 TYPE=INPUT:SUBMIT FORM=ID:enterAreaCodeForm ATTR=*');
        array_push($macro, 'EVENT TYPE=KEYPRESS SELECTOR="#enterAreaCodeForm>DIV>INPUT" KEY=39');
        array_push($macro, 'WAIT SECONDS=10');

        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="HTML>BODY>DIV:nth-of-type(5)>DIV>DIV>DIV:nth-of-type(2)>DIV>DIV>DIV>FORM>DIV>INPUT" BUTTON=0');
        array_push($macro, 'TAG POS=1 TYPE=INPUT:TEXT FORM=ID:enterAreaCodeForm ATTR=* CONTENT=' . mt_rand(200,800));
        array_push($macro, 'EVENT TYPE=KEYPRESS SELECTOR="#enterAreaCodeForm>DIV>INPUT" KEY=39');
        array_push($macro, 'TAG POS=1 TYPE=INPUT:SUBMIT FORM=ID:enterAreaCodeForm ATTR=*');
        array_push($macro, 'EVENT TYPE=KEYPRESS SELECTOR="#enterAreaCodeForm>DIV>INPUT" KEY=39');
        array_push($macro, 'WAIT SECONDS=10');
        array_push($macro, 'REFRESH');
    
        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="HTML>BODY>DIV:nth-of-type(5)>DIV>DIV>DIV:nth-of-type(2)>DIV>DIV>DIV>FORM>DIV>INPUT" BUTTON=0');
        array_push($macro, 'TAG POS=1 TYPE=INPUT:TEXT FORM=ID:enterAreaCodeForm ATTR=* CONTENT=' . mt_rand(200,800));
        array_push($macro, 'EVENT TYPE=KEYPRESS SELECTOR="#enterAreaCodeForm>DIV>INPUT" KEY=39');
        array_push($macro, 'TAG POS=1 TYPE=INPUT:SUBMIT FORM=ID:enterAreaCodeForm ATTR=*');
        array_push($macro, 'EVENT TYPE=KEYPRESS SELECTOR="#enterAreaCodeForm>DIV>INPUT" KEY=39');
        array_push($macro, 'WAIT SECONDS=5');

        array_push($macro, 'TAB CLOSEALLOTHERS');
        array_push($macro, 'TAB OPEN');
        array_push($macro, 'TAB T=2');
        array_push($macro, 'URL GOTO=https://receive-sms.com/admin/updatenumbersmacro/2/' . $numberid . '/' . $valfix . '/{{!VAR1}}');
 
     
 
        $this->indexMacro($macro);
        return $this->runMacro(true);

    }

    if ($stage == "2" and $ret == "no" and strpos($fix1, "messaging") !== false){

        number::where('id', '=', $id)->update(['info' => 'LOGGED IN']);

        $nr = number::where('id', '=', $id)->first();
        $pos = strpos($nr['network_login'], "@");

        array_push($macro, 'TAB CLOSE');

        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="HTML>BODY>DIV:nth-of-type(5)>DIV>DIV>DIV:nth-of-type(2)>DIV>DIV>DIV>FORM>DIV>INPUT" BUTTON=0');
        array_push($macro, 'TAG POS=1 TYPE=INPUT:TEXT FORM=ID:enterAreaCodeForm ATTR=* CONTENT=' . mt_rand(200,800));
        array_push($macro, 'EVENT TYPE=KEYPRESS SELECTOR="#enterAreaCodeForm>DIV>INPUT" KEY=39');
        array_push($macro, 'TAG POS=1 TYPE=INPUT:SUBMIT FORM=ID:enterAreaCodeForm ATTR=*');
            
            array_push($macro, 'SET !TIMEOUT_STEP 20');
            //array_push($macro, 'TAG POS=1 TYPE=DIV ATTR=CLASS:"*antigate_solver*solved*"');
        



        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#modal" BUTTON=0');
        array_push($macro, 'WAIT SECONDS=1');
        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#settings-nav>DIV>IMG" BUTTON=0');
        array_push($macro, 'WAIT SECONDS=1');
        array_push($macro, 'SET !EXTRACT NULL'); 
        array_push($macro, 'TAG SELECTOR="#tnDialogContainer>DIV:nth-of-type(2)>DIV>DIV>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV>DIV>DIV>P:nth-of-type(2)" EXTRACT=TXT'); 
        array_push($macro, 'SET !VAR1 https://receive-sms.com/admin/updatenumber/' . $numberid . '/'. $valfix);

        array_push($macro, 'TAG SELECTOR="#tnDialogContainer>DIV:nth-of-type(2)>DIV>DIV>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV>DIV:nth-of-type(2)>INPUT" CONTENT='. $first_name);
        array_push($macro, 'TAG SELECTOR="#tnDialogContainer>DIV:nth-of-type(2)>DIV>DIV>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV>DIV:nth-of-type(3)>INPUT" CONTENT='. $last_name);
        array_push($macro, 'WAIT SECONDS=2');
        array_push($macro, 'TAG SELECTOR="#tnDialogContainer>DIV:nth-of-type(2)>DIV>DIV>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV>DIV:nth-of-type(4)>INPUT" CONTENT='. $new_email);
        array_push($macro, 'WAIT SECONDS=4');
        array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#tnDialogContainer>DIV:nth-of-type(2)>DIV>DIV>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV>DIV:nth-of-type(5)>BUTTON" BUTTON=0');
        array_push($macro, 'WAIT SECONDS=6');
        array_push($macro, 'SET !EXTRACT NULL'); 
        array_push($macro, 'TAG SELECTOR="#tnDialogContainer>DIV:nth-of-type(2)>DIV>DIV>DIV>DIV>DIV>DIV" EXTRACT=TXT');
        
        if($pos == false){
            array_push($macro, 'ADD !VAR1 ' . '/' . $new_email . '/' . $myPassword .  '/' . $valfix);
        }else{
            array_push($macro, 'ADD !VAR1 ' . '/' . $new_email . '/' . urlencode($password) .  '/' . $valfix);
        }
        

        array_push($macro, 'SET !EXTRACT NULL'); 
        array_push($macro, 'TAG SELECTOR="#tnDialogContainer>DIV:nth-of-type(2)>DIV>DIV>DIV>DIV:nth-of-type(2)>DIV:nth-of-type(2)>DIV>DIV>DIV:nth-of-type(4)>INPUT" EXTRACT=TXT'); 
        array_push($macro, 'ADD !VAR1 ' . '/' . $valfix);

        if($pos == false){
            array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#notifications" BUTTON=0');
            array_push($macro, 'WAIT SECONDS=3');
            array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#email" BUTTON=0');
            array_push($macro, 'WAIT SECONDS=3');
            array_push($macro, 'EVENT TYPE=CLICK SELECTOR="#password" BUTTON=0');
            array_push($macro, 'WAIT SECONDS=2');
            array_push($macro, 'TAG POS=1 TYPE=INPUT ATTR=NAME:oldPassword CONTENT=' . $password);
            array_push($macro, 'TAG POS=1 TYPE=INPUT ATTR=NAME:newPassword CONTENT=' . $myPassword);
            array_push($macro, 'TAG POS=1 TYPE=INPUT ATTR=NAME:confirmPassword CONTENT=' . $myPassword);
            array_push($macro, 'WAIT SECONDS=2');
            array_push($macro, 'TAG POS=1 TYPE=BUTTON ATTR=TXT:Save');
            array_push($macro, 'WAIT SECONDS=10');
        }

        array_push($macro, 'URL GOTO={{!VAR1}}'); 
        array_push($macro, 'WAIT SECONDS=3');
        array_push($macro, 'URL GOTO=https://www.receive-sms.com/close');
        array_push($macro, 'WAIT SECONDS=3');
        array_push($macro, 'TAB CLOSE');

        array_push($macro, 'TAB CLOSE');

        $this->indexMacro($macro);
        return $this->runMacro(true);
        
    }else{
        
        
        $new_date = Carbon::now()->subDays(2)->toDateTimeString();
        number::where('id', '=', $id)->update(['last_checked' => $new_date]);
        number::where('id', '=', $id)->update(['info' => $ret]);

        if ($ret == "Username" or $ret == "This account has violated our terms of use, and the account and number are no longer available."){
            $nr = number::where('id', '=', $id)->first();
            if($nr['email'] == null or $nr['email'] == ""){
                number::where('id', '=', $id)->delete();
            }
        }

        //number::where('id', '=', $id)->update(['network_login' => "aa@expired.com"]);
        //return redirect('/admin/updatenumbersmacro/');
        array_push($macro, 'URL GOTO=https://www.receive-sms.com/close');
        array_push($macro, 'WAIT SECONDS=3');
        array_push($macro, 'TAB CLOSE');
        array_push($macro, 'TAB CLOSE');
        $this->indexMacro($macro);
        return $this->runMacro(true);
    
    }
        


}

public function runMacro($isFirst = false){

    $lines = macro::all()
    ->sortBy('id')
    ->pluck('line')
    ->take(43)
    ->toArray();

    $deleteIds = macro::all()
    ->sortBy('id')
    ->pluck('id')
    ->take(43)
    ->toArray();

    macro::destroy($deleteIds);

    $macro = '';
    $macro = $macro . 'SET !ERRORIGNORE YES' . '\r\n'; 
    $macro = $macro . 'SET !EXTRACT_TEST_POPUP NO'. '\r\n'; 
    if ($isFirst === false){$macro = $macro . 'TAB CLOSE'. '\r\n'; }


    
    foreach ($lines as $line) {
        $line =  str_replace('"','\"', $line);
        $macro = $macro . $line . '\r\n';
    }

    //for large macros
    //$macro = $macro . 'TAB CLOSEALLOTHERS' . '\r\n';
    //$macro = $macro . 'TAB OPEN' . '\r\n'; 
    //$macro = $macro . 'TAB T=2' . '\r\n'; 
    //$macro = $macro . 'URL GOTO=https://receive-sms.com/admin/runmacro' . '\r\n'; 


    return view("admin.macro")->with('code',$macro);
}


}
