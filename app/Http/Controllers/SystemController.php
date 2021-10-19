<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\Jobs\SendBroadcastJob;
use App\Mail\sendBroadcastEnrolmentIsOpen;
use App\Mail\sendConvocation;
use App\Mail\sendGeneralEmail;
use App\Mail\sendReminderToCurrentStudents;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\Teachers;
use App\Term;
use App\Text;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Support\Facades\Validator;

class SystemController extends Controller
{
    public function systemIndex()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $term = Term::where('Term_Code', Session::get('Term'))->first();
        $texts = Text::get();
        $onGoingTermObj = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        $onGoingTerm = Term::where('Term_Code', $onGoingTermObj->Term_Code)->first();

        return view('system.system-index', compact('terms', 'term', 'texts', 'onGoingTerm'));
    }

    public function sendGeneralEmail(Request $request)
    {
        // query students who have logged in
        $query_email_addresses = User::where('must_change_password', 0)
            ->where('mailing_list', 1)
            ->select('email')
            ->groupBy('email')
            ->get()
            ->pluck('email');

        $term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Create a valid term.');
            return redirect()->back();
        }

        $query_students_current_year = Repo::where('Term', $term->Term_Code)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $merge = $query_email_addresses->merge($query_students_current_year);
        $unique_email_address = $merge->unique();

        // dd($merge->unique());

        // $sddextr_email_address = 'allyson.frias@gmail.com';
        foreach ($unique_email_address as $sddextr_email_address) {
            Mail::to($sddextr_email_address)->send(new sendGeneralEmail($sddextr_email_address));
        }

        $request->session()->flash('success', 'Email sent!');
        return redirect()->back();
    }

    public function sendEmailToEnrolledStudentsOfSelectedTerm(Request $request)
    {
        $term = Session::get('Term');
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Select a valid term.');
            return redirect()->back();
        }

        $query_students_regular_enrolment = Preenrolment::where('Term', $term)
            ->where('overall_approval', 1)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $query_students_regular_placement = PlacementForm::where('Term', $term)
            ->where('overall_approval', 1)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $merge = $query_students_regular_enrolment->merge($query_students_regular_placement);
        $unique_email_address = $merge->unique();

        $countOfEmails = count($unique_email_address);
        // dd($term, $query_students_regular_enrolment, $query_students_regular_placement, $unique_email_address);

        // $sddextr_email_address = 'allyson.frias@gmail.com';
        foreach ($unique_email_address as $sddextr_email_address) {
            Mail::to($sddextr_email_address)->send(new sendGeneralEmail($sddextr_email_address));
        }

        $request->session()->flash('success', 'Email sent to ' . $countOfEmails . ' students!');
        return redirect()->back();
    }

    public function sendGeneralEmailToConvokedStudentsOfSelectedTerm(Request $request)
    {
        $term = Session::get('Term');
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Select a valid term.');
            return redirect()->back();
        }
        $convocation_all = Repo::where('Term', Session::get('Term'))->get();
        // with('classrooms')->get()->pluck('classrooms.Code', 'CodeIndexIDClass');

        // query students who will be put in waitlist
        $convocation_waitlist = Repo::where('Term', Session::get('Term'))->whereHas('classrooms', function ($query) {
            $query->whereNull('Tch_ID')
                ->orWhere('Tch_ID', '=', 'TBD');
        })
            ->get();

        // query students who will receive convocation
        $convocation = Repo::where('Term', Session::get('Term'))->whereHas('classrooms', function ($query) {
            $query->whereNotNull('Tch_ID')
                ->where('Tch_ID', '!=', 'TBD');
        })
            // ->where('Te_Code','!=','F3R2')
            ->get();

        $convocation_diff = $convocation_all->diff($convocation);
        $convocation_diff2 = $convocation_waitlist->diff($convocation_diff);
        $convocation_diff3 = $convocation->diff($convocation_waitlist); // send email convocation to this collection

        $countOfEmails = $convocation_diff3->count();
        // $sddextr_email_address = 'allyson.frias@gmail.com';
        foreach ($convocation_diff3 as $value) {
            Mail::to($value->users->email)->send(new sendGeneralEmail($value->users->email));
        }
        
        $request->session()->flash('success', 'Email sent to ' . $countOfEmails . ' students!');
        return redirect()->back();
    }

    /**
     * Send broadcast reminder email to all students who have logged in
     * Use during START of enrolment 
     * @param  Request $request 
     * @return HTML Closure           
     */
    public function sendBroadcastEnrolmentIsOpen(Request $request)
    {
        // query students who have logged in
        $query_email_addresses = User::where('must_change_password', 0)
            ->where('mailing_list', 1)
            ->select('email')
            ->groupBy('email')
            ->get()
            ->pluck('email');

        $term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Create a valid term.');
            return redirect()->back();
        }

        $query_students_current_year = Repo::where('Term', $term->Term_Code)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $merge = $query_email_addresses->merge($query_students_current_year);
        $unique_email_address = $merge->unique();
        // $unique_email_address = $unique_email_address->toArray();

        // dd($merge->unique());

        // $sddextr_email_address_sample = ['annegretbrauss@web.de-','allyson.frias@gmail.com','kkepaladin@yahoo.com'];
        // $emailArrayIterator = new \ArrayIterator($sddextr_email_address_sample);
        // foreach (new \LimitIterator($emailArrayIterator, 1) as $email) {
        //     var_dump($email);
        // }

        // $emailArrayIterator = new \ArrayIterator($unique_email_address);
        $emailError = [];
        $validEmails = [];
        // foreach (new \LimitIterator($emailArrayIterator, 252) as $sddextr_email_address) {
        $start = microtime(true);
        foreach ($unique_email_address as $sddextr_email_address) {
            $my_data = [ 'email' => $sddextr_email_address,];
            $validator = Validator::make($my_data, [
                'email' => 'email',
            ]);
            if ($validator->fails()) {
                $emailError[] = $sddextr_email_address;
            } 
            $validEmails[] = $sddextr_email_address;
        }
        $unique_email_address_valid = $validEmails;

        $sent = [ "13SNEHIL83@GMAIL.COM", 
                    "20uejima20@gmail.com", 
                    "2291547115@qq.com", 
                    "379267577@qq.com", 
                    "5kpaces@gmail.com", 
                    "a.bespayev@kazakhstan-geneva.ch", 
                    "a.bulatnikova@gmail.com", 
                    "a.bycroft@dcaf.ch", 
                    "a.cuneo@ioldcs.org", 
                    "a.dadkhah86@gmail.com", 
                    "a.djupedal@igc.ch", 
                    "a.habibi@mfa.af", 
                    "a.moncarey@delwalbru.be", 
                    "a.shields@upr-info.org", 
                    "aaladysheva@icrc.org", 
                    "aaltinealio@icrc.org", 
                    "AALTUNOGLU@WMO.INT", 
                    "aandreuvillasevil@icrc.org", 
                    "AAVETISYAN@OHCHR.ORG", 
                    "aayamgha@unicef.org", 
                    "abahraminassab@icrc.org", 
                    "abaillat@iom.int", 
                    "ABALASINGHAM@OHCHR.ORG", 
                    "abalzerano@unog.ch", 
                    "abdifatah.subow@un.org", 
                    "abebe20@un.org", 
                    "abekmakhanov@iom.int", 
                    "abel.martinezgomez@un.org", 
                    "abelab@who.int", 
                    "abentobbal@ohchr.org", 
                    "aberaw@unaids.org", 
                    "abernal@ohchr.org", 
                    "abhay.singh@un.org", 
                    "abhola@ohchr.org", 
                    "abihachem@un.org", 
                    "abinhimd@ohchr.org", 
                    "abiola.olanipekun@brsmeas.org", 
                    "abolanos@protonmail.com", 
                    "aborrel@unicef.org", 
                    "aboubacar@intracen.org", 
                    "abouhamra@un.org", 
                    "abrauss@intracen.org", 
                    "abs@ipsgeneva.com", 
                    "abuaziz@un.org", 
                    "abubarhamh@un.org", 
                    "abulatov@ohchr.org", 
                    "abushhiwa1970@gmail.com", 
                    "ABZEITH@ICRC.ORG", 
                    "acastillocastrillo@icrc.org", 
                    "ACATCHESIDE@WMO.INT", 
                    "acevallos@ohchr.org", 
                    "acfontan@wmo.int", 
                    "achalmriganka@gmail.com", 
                    "achille@despres.ch", 
                    "ackerm@un.org", 
                    "acl@ipu.org", 
                    "aclarke@ohchr.org", 
                    "acorreiareis@unicef.org", 
                    "Adam.Kane@unctad.org", 
                    "adama.coulibaly@un.org", 
                    "adams@unaids.org", 
                    "addihinfos@gmail.com", 
                    "adeasis@icrc.org", 
                    "adeeb.ahsan007@yahoo.com", 
                    "adel.salama@un.org", 
                    "adelju@wmo.int", 
                    "adevos@ohchr.org", 
                    "adiaz@intracen.org", 
                    "adieci@ohchr.org", 
                    "adil@unhcr.org", 
                    "Adlung@unhcr.org", 
                    "adobrogowska@unicef.org", 
                    "adolsgarcia@ohchr.org", 
                    "adrian.hassler@posteo.de", 
                    "adriana.diazfuenmayor@un.org", 
                    "adriana.fuentes@un.org", 
                    "adriana.sa@outlook.com", 
                    "adriano.timossi@fao.org", 
                    "adriennemgibbs1@gmail.com", 
                    "Adullard@icrc.org", 
                    "AELHOUDERI@IOM.INT", 
                    "aeljundi@ohchr.org", 
                    "aelshekh@icrc.org", 
                    "aeroe@intracen.org", 
                    "aesangbedo@unicef.org", 
                    "aeshel@ohchr.org", 
                    "af.lipman@orange.fr", 
                    "afghan.mission2wto@gmail.com", 
                    "agachie@ohchr.org", 
                    "agardaz@ohchr.org", 
                    "aghanem@unicef.org", 
                    "aghoshal@unicef.org", 
                    "AGILBERT@OHCHR.ORG", 
                    "agnes.duprelatour@un.org", 
                    "agoldman@medicinespatentpool.org", 
                    "agomez@unicef.org", 
                    "AGRIGORAS@OHCHR.ORG", 
                    "aguilarcortinam@who.int", 
                    "ah4145@nyu.edu", 
                    "ahashayka@ohchr.org", 
                    "ahattori@ohchr.org", 
                    "bastian.moulin@eda.admin.ch", 
                    "batalhone@intracen.org", 
                    "batistab@un.org", 
                    "batmunkh.ganbold@un.org", 
                    "bauer@uicc.org", 
                    "baureder@unhcr.org", 
                    "bawesim@gmail.com", 
                    "bazebosso@un.org", 
                    "bb.dezsi@gmail.com", 
                    "bbayarlkham@gmail.com", 
                    "BBEISKJAER@UNICEF.ORG", 
                    "bbeuchel@intracen.org", 
                    "Beate.Giffoschmitt@wipo.int", 
                    "BEATRICE.KALUMBA@UN.ORG", 
                    "beatrice.tisato@un.org", 
                    "beatrice@icanw.org", 
                    "beatriz.tupia@un.org", 
                    "becker@unhcr.org", 
                    "befecadu@intracen.org", 
                    "begonya.mora-rubio@un.org", 
                    "begumk@unops.org", 
                    "beibei.gu@un.org", 
                    "bekele24@un.org", 
                    "belaskri@unhcr.org", 
                    "belhaddassi@unog.ch", 
                    "beltramo@unhcr.org", 
                    "ben.riemenschneider@un.org", 
                    "benedict.rimando@un.op.org", 
                    "benedicte.boudol@un.org", 
                    "benedicte.saouter@un.org", 
                    "benedikt_luecke@yahoo.de", 
                    "benito.jimeno@un.org", 
                    "benjamin.bloom@wto.org", 
                    "benjamin.nominet@un.org", 
                    "benjamin.syme@wfp.org", 
                    "benmakhl@unhcr.org", 
                    "benny.salo@un.org", 
                    "benoit.aperce@wipo.int", 
                    "benoudina@intracen.org", 
                    "benshea@gmail.com", 
                    "benzakri@intracen.org", 
                    "berengere.neyroudplugel@un.org", 
                    "berenyi@unhcr.org", 
                    "berlingc@who.int", 
                    "BERNIER@UNHCR.ORG", 
                    "berov@un.org", 
                    "bertomeu@uicc.org", 
                    "bertrand.stivalet@esial.net", 
                    "betsen.baby@un.org", 
                    "betsy.ntongai@un.org", 
                    "bettina.spilker@un.org", 
                    "beulah.chelva@un.org", 
                    "bevan.chishimba@wipo.int", 
                    "beytul.gorkem@fightforhumanity.org", 
                    "bgenolet@ohchr.org", 
                    "bhussey@icrc.org", 
                    "bhuwanee@intracen.org", 
                    "biaalbernaz@gmail.com", 
                    "biantz2mampungu@gmail.com", 
                    "bibianaaz@stoptb.org", 
                    "Bijeje@unhcr.org", 
                    "billat@unhcr.org", 
                    "billclucas@me.com", 
                    "bin.lai@un.org", 
                    "Bineswaree.Bolaky@unctad.org", 
                    "bing.dai@un.org", 
                    "bing.xu@un.org", 
                    "BIRGITTA.HOLMSTROM@GOV.SE", 
                    "bisau@un.org", 
                    "bismark.sitorus@un.org", 
                    "bissacot@unhcr.org", 
                    "bjamal@ohchr.org", 
                    "bjampathai@icrc.org", 
                    "bjebeli@ohchr.org", 
                    "bkangwa@unicef.org", 
                    "bl@ipu.org", 
                    "blackburna@un.org", 
                    "blakec2@state.gov", 
                    "blalock@un.org", 
                    "blanca.zutta@wto.org", 
                    "blandine.legardeur@un.org", 
                    "bleisha@gmail.com", 
                    "bliu@wmo.int", 
                    "blommestijn@un.org", 
                    "blutfiu@icrc.org", 
                    "bmcdonald@iom.int", 
                    "BNORONHA@WMO.INT", 
                    "bob.bell@un.org", 
                    "bohl@un.org", 
                    "boi-lan.lemoine@un.org", 
                    "bold.nergui@gmail.com", 
                    "boormancx@state.gov", 
                    "BORINA@ICRC.ORG", 
                    "bostjan.skalar@waipa.org", 
                    "BOU@INTRACEN.ORG", 
                    "boubacarhamani@un.org", 
                    "boudria@un.org", 
                    "boukezouha@unfpa.org", 
                    "boulmaoui@un.org", 
                    "bounadarym@yahoo.fr", 
                    "bouvoir@un.org", 
                    "deepak.goel@un.org", 
                    "deeptindel@gmail.com", 
                    "deirdre.mcbride@dfa.ie", 
                    "dekrout@unhcr.org", 
                    "DELACRUZ@UNHCR.ORG", 
                    "delanghe@unhcr.org", 
                    "delfina.cuglievan@un.org", 
                    "delia.kosset@gmail.com", 
                    "delic@unhcr.org", 
                    "delphine.jarraud@un.org", 
                    "delphine.martinot@un.org", 
                    "Delphine.Mayen@unctad.org", 
                    "delphine.schiavo@un.org", 
                    "denis.dominguezcorcoba@un.org", 
                    "denis.komarov@un.org", 
                    "Denise.Penello.Rial@unctad.org", 
                    "Deniz.Barki@unctad.org", 
                    "dennis.winkler@un.org", 
                    "denoiseux@un.org", 
                    "DEOLIVEIRAKUNIEDA@UN.ORG", 
                    "deputy@panama-omc.ch", 
                    "dequn.jiang1@un.org", 
                    "derlot@intracen.org", 
                    "dermont@unhcr.org", 
                    "derosac@un.org", 
                    "desimone@ilo.org", 
                    "deutschl@unhcr.org", 
                    "deyanakosta@gmail.com", 
                    "dfeibert@icrc.org", 
                    "dglee812@gmail.com", 
                    "dhafer@un.org", 
                    "dhamadeh@icrc.org", 
                    "dhanuk@un.org", 
                    "dherhayo@gmail.com", 
                    "di-mawete.yanga@wto.org", 
                    "diallo22@un.org", 
                    "diana.manilla@ifrc.org", 
                    "diana.ongiti@ifrc.org", 
                    "diana.rosert@unctad.org", 
                    "DIANA.TOMIMURA@ITU.INT", 
                    "DIANA@ILGA.ORG", 
                    "diarra.dime-labille@diplomatie.gouv.fr", 
                    "diarra4@un.org", 
                    "diarra50@un.org", 
                    "diazsanm@unhcr.org", 
                    "dicapua@uicc.org", 
                    "dicelis@un.org", 
                    "didier.chessel@un.org", 
                    "diego.costafernandes@un.org", 
                    "diego.valadaresvasconcelosneto@un.org", 
                    "DIEGONPEREZV@GMAIL.COM", 
                    "Dilbar.khakimova@gmail.com", 
                    "dimechkie@ilo.org", 
                    "dina.hajj@undp.org", 
                    "dingdingchao@gmail.com", 
                    "dipoov@gmail.com", 
                    "DIPRETOR@UNHCR.ORG", 
                    "directeur@fcigeneve.ch", 
                    "dirk.co@un.org", 
                    "divecha@unhcr.org", 
                    "DIVYAMDEE@GMAIL.COM", 
                    "djamel.benkrid@un.org", 
                    "djamila.terfous@un.org", 
                    "djasngar@un.org", 
                    "djerabe@un.org", 
                    "djerrycosta@gmail.com", 
                    "djimadjarade@un.org", 
                    "djouma@un.org", 
                    "dkabieva@mail.ru", 
                    "dkdey@unicef.org", 
                    "dkeserovic@iom.int", 
                    "dkhuukhenbaatar@wmo.int", 
                    "dkirby@ohchr.org", 
                    "dli@who.int", 
                    "DLOCKETT@WMO.INT", 
                    "dlopez@ohchr.org", 
                    "dmaccormack@gmail.com", 
                    "dmitry.biryukov@ifrc.org", 
                    "dmitry.mariyasin@un.org", 
                    "dmochizuki@iom.int", 
                    "dmolinuevo@unicef.org", 
                    "dmporamazina@icrc.org", 
                    "dmurillo@rree.go.cr", 
                    "dnemmas@icrc.org", 
                    "dngoga@ohchr.org", 
                    "domarlekoudous-singa@un.org", 
                    "dominguezj@ilo.org", 
                    "dominici@unhcr.org", 
                    "dominika.tomaszewska-mortimer@un.org", 
                    "dominique.chantrel@un.org", 
                    "dominique.vanzo@un.org", 
                    "don.martin@un.org", 
                    "don.spedding@gmail.com", 
                    "Dong.Wu@unctad.org", 
                    "donna.el-murr@un.org", 
                    "donnalwilliams_1@yahoo.com", 
                    "dopwonya@icrc.org", 
                    "doreen.yomoah@un.org", 
                    "dorian.hall@plan-international.org", 
                    "doriane.mollard-minnebois@un.org", 
                    "dorijanusa@hotmail.com", 
                    "ghorayebc@un.org", 
                    "giacomo.dinoto@un.org", 
                    "gianluca.espa@un.org", 
                    "gibo771@yahoo.co.uk", 
                    "gideon.duplessis@un.org", 
                    "gilles.sereni@un.org", 
                    "gilleyhj@gmail.com", 
                    "gillian_kenyon@yahoo.com", 
                    "gillieron@un.org", 
                    "gincer@iom.int", 
                    "giorgia.corno@icloud.com", 
                    "giorgia.sacco@un.org", 
                    "giorgio.pieretti@un.org", 
                    "giovanna.chiodi@un.org", 
                    "giovanna.yazdani@un.org", 
                    "giovanni.napolitano@wipo.int", 
                    "giulia.tempo@un.org", 
                    "giuseppe.conti@wto.org", 
                    "GJAFAROVA@UNICEF.ORG", 
                    "gjargalsaihan@unicef.org", 
                    "gkamalova@unicef.org", 
                    "glassk@un.org", 
                    "glennapolinar@gmail.com", 
                    "gloria.delarosa@un.org", 
                    "gloria.rubioguifarro@wto.org", 
                    "glorinna@yahoo.de", 
                    "gmapanga@ohchr.org", 
                    "gmentxaka@unicef.org", 
                    "gmurray@unicef.org", 
                    "gneidhardt@ohchr.org", 
                    "GNYABOKE6@GMAIL.COM", 
                    "goelless@un.org", 
                    "gokpinar@un.org", 
                    "goldschmidtv@un.org", 
                    "golubic@intracen.org", 
                    "goma.luis@gmail.com", 
                    "gondwe.g@gmail.com", 
                    "gonsalvesl@who.int", 
                    "gonzalezo@unaids.org", 
                    "GONZALEZORTEGA@INTRACEN.ORG", 
                    "GOODMANMJ@STATE.GOV", 
                    "gopalang@un.org", 
                    "gordon2@un.org", 
                    "gouirann@unaids.org", 
                    "gouyou@unhcr.org", 
                    "govil@unhcr.org", 
                    "GOVINDANKUTTY@UN.ORG", 
                    "gpaillot@unog.ch", 
                    "gpajuelo@onuperu.org", 
                    "gpillay@seymission.ch", 
                    "graham.alabaster@un.org", 
                    "Graham.Mott@unctad.org", 
                    "GREGG.CAPORASO@GMAIL.COM", 
                    "gregory.macdonald@international.gc.ca", 
                    "gregory.zanetton@un.org", 
                    "GREGORYCONNOR@HOTMAIL.COM", 
                    "gregoryl@unops.org", 
                    "greta.keenan@weforum.org", 
                    "griffin@intracen.org", 
                    "grirodriguez@mire.gob.pa", 
                    "grodriguez@intracen.org", 
                    "gronnevet@protonmail.com", 
                    "groonis@unhcr.org", 
                    "GROSS@UN.ORG", 
                    "groussel@wmo.int", 
                    "gsanchez@ohchr.org", 
                    "gsijpogeneva@gmail.com", 
                    "gtheissen@ohchr.org", 
                    "gtlorente@gmail.com", 
                    "guedenet@un.org", 
                    "guerrierd@un.org", 
                    "gugliottas@unaids.org", 
                    "guicovsky@intracen.org", 
                    "guilhermegduarte@gmail.com", 
                    "guillaume.gay@un.org", 
                    "guillaume.marneffe@un.org", 
                    "guillaume2gonat@gmail.com", 
                    "gul.unal@un.org", 
                    "gulick@unhcr.org", 
                    "gulnora.azizova@un.org", 
                    "Guoyong.Liang@unctad.org", 
                    "guy.mettan@gmail.com", 
                    "gvarro@wmo.int", 
                    "gwenn.ward@un.org", 
                    "gyeney@ilo.org", 
                    "GYUDAKOVA@YANDEX.RU", 
                    "gzambello@unicef.org", 
                    "habib.turki@hotmail.fr", 
                    "hackel@un.org", 
                    "hadjaro@un.org", 
                    "hadjsaid868@gmail.com", 
                    "haekim@iom.int", 
                    "hafyan@un.org", 
                    "hagossa@un.org", 
                    "haidery@un.org", 
                    "HAIJUAN.YU@IFRC.ORG", 
                    "haimisha@gmail.com", 
                    "hakan.ertem@mfa.gov.tr", 
                    "hakim.hadjel@wsscc.org", 
                    "hakullo@unicef.org", 
                    "halkhatib@icrc.org", 
                    "jude.mariani@itu.int", 
                    "judit.revesz@ifrc.org", 
                    "Judith.Leclercq@unctad.org", 
                    "judy.balaratnam@wipo.int", 
                    "judy.fadel-ostojic@un.org", 
                    "juliam@stoptb.org", 
                    "julian.fraga-campos@unctad.org", 
                    "juliana.helou@un.org", 
                    "julie.nevski@un.org", 
                    "julie.nge@un.org", 
                    "julielina@gmail.com", 
                    "julien.sylvestre-fleury@international.gc.ca", 
                    "juliette.blouin@un.org", 
                    "juliette.mhadjou@unjspf.org", 
                    "julio.calvo@itu.int", 
                    "julio.pinto@un.org", 
                    "julius.birungi@un.org", 
                    "julius.brandes@un.org", 
                    "jung6@un.org", 
                    "JUNIOR.DAVIS@UN.ORG", 
                    "juniormopoka@gmail.com", 
                    "junxiang.zhao@un.org", 
                    "jurabek@gmail.com", 
                    "justine.micallef@gov.mt", 
                    "jvanweelde@intracen.org", 
                    "jwang@ohchr.org", 
                    "jwkim0217@gmail.com", 
                    "jworrell@ohchr.org", 
                    "jyrki.hirvonen@un.org", 
                    "jzietemann@intracen.org", 
                    "k.belarroud@gmail.com", 
                    "k.cormier.ribout@gmail.com", 
                    "K.RYAN@IGC.CH", 
                    "k.velcikova@gmail.com", 
                    "kacel@unicc.org", 
                    "KACHASTAD@WP.PL", 
                    "kachourin@who.int", 
                    "kaczmare@unhcr.org", 
                    "kahindo@un.org", 
                    "kahn@intracen.org", 
                    "kai.suelzle@un.org", 
                    "kaitlynn.newcomb@un.org", 
                    "kajsa.aulin@gov.se", 
                    "KAKANDE-ALASOKA@WMO.INT", 
                    "kaleer@ohchr.org", 
                    "kali.taylor@un.org", 
                    "kalmykov-dm@list.ru", 
                    "kamahmoud@icrc.org", 
                    "kamal.tahiri@un.org", 
                    "kamalyah85@gmail.com", 
                    "kamarudi@unhcr.org", 
                    "kamola.khusnutdinova@un.org", 
                    "kangw@un.org", 
                    "kanjilald.debjani@gmail.com", 
                    "kannan@unhcr.org", 
                    "kantea@unaids.org", 
                    "kaoru.sugiura@mofa.go.jp", 
                    "KAPLINAANNA555@HOTMAIL.COM", 
                    "karam.al-hadeethi@un.org", 
                    "karelboers56@gmail.com", 
                    "karen.gaynor@un.org", 
                    "Karen.Mulweye@unctad.org", 
                    "karen.seaman@un.org", 
                    "karen.taylor@un.org", 
                    "karim.elgaouzi@un.org", 
                    "karin.penteker@wipo.int", 
                    "karina.alvespina@un.org", 
                    "KARINA.PEREZROUCO@IFRC.ORG", 
                    "karine.jean-marie@wto.org", 
                    "kariukim@unhcr.org", 
                    "karla.lienhart@undp.org", 
                    "KARLICA@UN.ORG", 
                    "karyna.kasimova@un.org", 
                    "kassah@who.int", 
                    "kastlander@un.org", 
                    "katalin.bokor@un.org", 
                    "katarina.palairet@gmail.com", 
                    "katarzyna.kaszubska@un.org", 
                    "katarzyna.stecz@mpit.gov.pl", 
                    "kate.viriyamettakul@un.org", 
                    "kateryna.bakulina@mfa.gov.ua", 
                    "kateryna.riabchenko@un.org", 
                    "Katherine.bueno@etat.ge.ch", 
                    "KATHERINE.REWINKELEL-DARWISH@WTO.ORG", 
                    "kathryn.hennessey@un.org", 
                    "KATHRYN.LUNDQUIST@WTO.ORG", 
                    "kathryn.pitcher@un.org", 
                    "katia.pladiaz@wipo.int", 
                    "Katia.Vieu@unctad.org", 
                    "katri.veldre@un.org", 
                    "katrina.hardie@un.org", 
                    "kaur@intracen.org", 
                    "kawtar.lahlou@un.org", 
                    "kayalekesizgoz@gmail.com", 
                    "kayci.browne@un.org", 
                    "kayla.kim@un.org", 
                    "kayondo89@gmail.com", 
                    "kayosuzuki_50@hotmail.com", 
                    "kazdaileviciene@un.org", 
                    "kazhin.hasan@un.org", 
                    "kazunori.fukuda@mofa.go.jp", 
                    "mandabam@un.org", 
                    "mandrijasevic-boko@ohchr.org", 
                    "mandronic@unicef.org", 
                    "manduhaiub@yahoo.com", 
                    "mandyyu0201@gmail.com", 
                    "mane2@un.org", 
                    "manjooran@un.org", 
                    "mankambadibaya@un.org", 
                    "manta@unhcr.org", 
                    "manuel.martinezmiralles@un.org", 
                    "MANUELA.BERNAL.REYES@GMAIL.COM", 
                    "mao1249@gmail.com", 
                    "maquita_22_10@hotmail.com", 
                    "marangozambrano@icrc.org", 
                    "marara@unhcr.org", 
                    "marcacci@unhcr.org", 
                    "marcantonio@un.org", 
                    "marce_rivera7@hotmail.com", 
                    "Marce167@hotmail.com", 
                    "marcela.clavijo@un.org", 
                    "marchi-uhel@un.org", 
                    "marco.araniva@me.com", 
                    "marco.avila@ifrc.org", 
                    "marek.gajdos@scalingupnutrition.org", 
                    "margarida.folch@un.org", 
                    "margarita.griffith@ifrc.org", 
                    "margarita.pirovska@un.org", 
                    "margarita.yordanova@un.org", 
                    "margherita.stevoli@gmail.com", 
                    "maria.bressi@wto.org", 
                    "maria.cruzpadron@un.org", 
                    "maria.delaplaza@un.org", 
                    "maria.gatmaytan@un.org", 
                    "maria.jimenezdeaguilar@un.org", 
                    "maria.kotsi@wto.org", 
                    "Maria.Luz.Jaureguiberry@unctad.org", 
                    "maria.lyakhovskaya@un.org", 
                    "maria.radetskaya@un.org", 
                    "maria.sorianoescolar@un.org", 
                    "mariadel.sanchez@un.org", 
                    "mariadelmar.moyatasis@un.org", 
                    "mariagrazia.fucile@gmail.com", 
                    "mariaisabel.rincon@wipo.int", 
                    "mariajose.lloret@un.org", 
                    "mariajose.orellana@un.org", 
                    "mariajose.setuainurtasun@un.org", 
                    "mariam.elmaghlawy@un.org", 
                    "mariam.h.traore@undp.org", 
                    "mariana.olivera.west@gmail.com", 
                    "mariana.voita@un.org", 
                    "Mariangela.Linoci@unctad.org", 
                    "mariasoledad2001@yahoo.com", 
                    "mariateresa.sapiente@wipo.int", 
                    "maricar.delacruz@un.org", 
                    "maricarmenlanfranco@gmail.com", 
                    "marie-agnes.deleschaux@un.org", 
                    "marie-andree.levesque@international.gc.ca", 
                    "marie-christine.bellossat@un.org", 
                    "marie-laure.blanc@eeas.europa.eu", 
                    "Marie-Lise.Morgan@unctad.org", 
                    "marie-rose.gerard@un.org", 
                    "MARIE.DURLING@SCALINGUPNUTRITION.ORG", 
                    "marie.guerraz@un.org", 
                    "marie.montant@un.org", 
                    "Marie.Sicat@unctad.org", 
                    "marieisabelle.pellan@wto.org", 
                    "mariejo.deraspe@itu.int", 
                    "MARIEL.LEZAMA@HONDURASGINEBRA.CH", 
                    "mariemdali@gmail.com", 
                    "mariia.sorokina@un.org", 
                    "marijana.todorovic@un.org", 
                    "marika.palosaari@unep.ch", 
                    "marimic12@gmail.com", 
                    "marina.birbaum@un.org", 
                    "marina.cartier-kayayan@un.org", 
                    "marinasm@unops.org", 
                    "marine.hutteau@un.org", 
                    "marine.vorlet@un.org", 
                    "mario.jales@un.org", 
                    "marion.vandereecken@un.org", 
                    "marius.wiher@brsmeas.org", 
                    "mariusflorian.gologus@un.org", 
                    "marizaga@ohchr.org", 
                    "marjorie.etinof@un.org", 
                    "mark.peacock@un.org", 
                    "Mark.Willis@unctad.org", 
                    "markus.schmidt@un.org", 
                    "MARLEN.SCHUEPBACH@SCALINGUPNUTRITION.ORG", 
                    "marlene.borlant@un.org", 
                    "marlene.Haustein@wipo.int", 
                    "marlitt.brandes@un.org", 
                    "marnus.vanzyl@un.org", 
                    "marrouchr@who.int", 
                    "marsteve@marsteve.net", 
                    "marta.erroz@unitar.org", 
                    "marta.hurtadogomez@un.org", 
                    "marta.urielarias@un.org", 
                    "martha_alanis@hotmail.com", 
                    "martin.guard@un.org", 
                    "martin.hitziger@un.org", 
                    "martin.mcconnachie@un.org", 
                    "nfranco@unicef.org", 
                    "ngabai@un.org", 
                    "ngendahayo2@un.org", 
                    "ngendre@iom.int", 
                    "ngohelp4help@gmail.com", 
                    "NGOUSSAC@ICRC.ORG", 
                    "nguyenthuyr@gmail.com", 
                    "nharris@iom.int", 
                    "nhurtado@ohchr.org", 
                    "nhuynh@unicef.org", 
                    "niamh.clarke@fco.gov.uk", 
                    "Niang3@un.org", 
                    "NIC0829@NAVER.COM", 
                    "nicastro.mirella@gmail.com", 
                    "nicholas.tan@un.org", 
                    "nicholas.theotocatos@un.org", 
                    "nicholsonj@who.int", 
                    "nicka_nordlund@hotmail.com", 
                    "nicola7platt@gmail.com", 
                    "nicolas.avilav@cancilleria.gov.co", 
                    "nicolas.buisson@un.org", 
                    "nicolas.deas@un.org", 
                    "nicolas.morin@un.org", 
                    "nicolas.ribello@orange.fr", 
                    "nicolas.russo@un.org", 
                    "nicolc@unaids.org", 
                    "nicole.drews@wipo.int", 
                    "nicole.harper@itu.int", 
                    "nicusorandrei.florea@un.org", 
                    "niematallah.ahmedelamin@un.org", 
                    "nijenhuis@un.org", 
                    "nijman@unhcr.org", 
                    "nikica.darabos@mvep.hr", 
                    "nikola.jovanovic@ifrc.org", 
                    "nikola.sahovic@un.org", 
                    "nikolay.lozinskiy@un.org", 
                    "nikolova.mimi@gmail.com", 
                    "NIKUSHEVA@INTRACEN.ORG", 
                    "niloofar.zand@international.gc.ca", 
                    "nir.amir@un.org", 
                    "nishanti.balashanmugam@ifrc.org", 
                    "nissalbe-kemaye@un.org", 
                    "nita.venturelli@un.org", 
                    "nitzan.naaman@un.org", 
                    "niverte.noberasco@un.org", 
                    "nixienu@gmail.com", 
                    "nkemaye35@gmail.com", 
                    "nkhatri@icrc.org", 
                    "nkuzmina@ohchr.org", 
                    "nlundgren@unicef.org", 
                    "nmajuva@hotmail.com", 
                    "nmeulders@ohchr.org", 
                    "NNBAYUROVA@MAIL.RU", 
                    "nngayap@ohchr.org", 
                    "nnishat@icrc.org", 
                    "noah.miller@un.org", 
                    "noblepatrickable@gmail.com", 
                    "noe.rousseau@un.org", 
                    "noemi.arencibia@itu.int", 
                    "noemi.cambray@theglobalfund.org", 
                    "noha.hafez@un.org", 
                    "nolnom@naver.com", 
                    "nonimunge@hotmail.com", 
                    "nonninge@unhcr.org", 
                    "nooru@un.org", 
                    "noppliger@wmo.int", 
                    "nora.godkin@un.org", 
                    "novak@unhcr.org", 
                    "noviera@un.org", 
                    "nozomitsuchio@yahoo.com", 
                    "npepanashvili@iom.int", 
                    "nramamonjisoa@ohchr.org", 
                    "NSAHOURI@OHCHR.ORG", 
                    "nschultz@ohchr.org", 
                    "NSLUGA@OHCHR.ORG", 
                    "nsofwa.ngona@un.org", 
                    "ntaal@intracen.org", 
                    "ntawuruh@unhcr.org", 
                    "nthisana.phillips@wto.org", 
                    "ntiroranya@un.org", 
                    "ntolo.ntolo@gmail.com", 
                    "nudhornp@hotmail.com", 
                    "nulsaqibiqbal@icrc.org", 
                    "nunezfer@unhcr.org", 
                    "nunezve@unhcr.org", 
                    "nuon@un.org", 
                    "NUSTAMALDONADO@GMAIL.COM", 
                    "nvanderwel@wmo.int", 
                    "NWAKA@UNHCR.ORG", 
                    "nwe@unhcr.org", 
                    "nwhite@unicef.org", 
                    "nyakurerwa@un.org", 
                    "nyamkhuuartan@un.org", 
                    "NYANDUGA@UNHCR.ORG", 
                    "nzete.dasamaitoua@un.org", 
                    "oagbaje@tv-tay.org", 
                    "oana.redinciuc@gmail.com", 
                    "oandt.hirano@gmail.com", 
                    "obaran@who.int", 
                    "obelbeisi@iom.int", 
                    "obrien2@un.org", 
                    "roy.hernandez@un.org", 
                    "roza.vanderheide@un.org", 
                    "rpainter@ohchr.org", 
                    "rpenaloza@intracen.org", 
                    "rpereira@misionparaguay.ch", 
                    "rpreturlan@ohchr.org", 
                    "rr@ipu.org", 
                    "rrahulmehrotra@gmail.com", 
                    "rramasamy@un.org", 
                    "rreddy@ohchr.org", 
                    "rriond@icrc.org", 
                    "RROSARIODESOUZA@OHCHR.ORG", 
                    "rshamdasani@ohchr.org", 
                    "rtatawidjaja@intracen.org", 
                    "rtd-intern@ohchr.org", 
                    "RTRIPATHI@WMO.INT", 
                    "ruatpuii.cira@gmail.com", 
                    "ruben.guillen@un.org", 
                    "ruichuan.yu@un.org", 
                    "rus.disarm@yandex.ru", 
                    "russor@unhcr.org", 
                    "ruta.rudinskaite@gmail.com", 
                    "ruth.blackshaw@un.org", 
                    "ruth.hetherington@gmail.com", 
                    "ruth.maquera-gagnebin@un.org", 
                    "rvallet@unicef.org", 
                    "RYANBROWN@UTEXAS.EDU", 
                    "rzhang@wmo.int", 
                    "rzhou@who.int", 
                    "s.azizi@mfa.af", 
                    "s.filipov@mission-bulgarie.ch", 
                    "s.zanardo@delwalbru.be", 
                    "saad12@un.org", 
                    "saad8@un.org", 
                    "SAADJ@UN.ORG", 
                    "sabdulrasak@unicef.org", 
                    "sabina.titarenko@iss-ssi.org", 
                    "sabouni@un.org", 
                    "sabri.sabara@un.org", 
                    "sabrina.mansion@un.org", 
                    "sacha.yabili@gmail.com", 
                    "sadaf.shamsie@un.org", 
                    "saeed5@un.org", 
                    "saeeda.verrall@un.org", 
                    "safa.dahab@un.org", 
                    "safari3@un.org", 
                    "SAFARLI@UNHCR.ORG", 
                    "SaffaturayS@unaids.org", 
                    "sagaydak@un.org", 
                    "sagnikch@yahoo.com", 
                    "sai.kham@un.org", 
                    "said@intracen.org", 
                    "saif.shaheen@un.org", 
                    "saija.andre@gmail.com", 
                    "saikalesengeldieva@gmail.com", 
                    "saki.tomita1002@gmail.com", 
                    "saktoprak@iom.int", 
                    "salda.samih@un.org", 
                    "saleh17@un.org", 
                    "saleh22@un.org", 
                    "salemabaiss@gmail.com", 
                    "saletta@unhcr.org", 
                    "salfiti@un.org", 
                    "sallakuj@who.int", 
                    "salma.abdalrhman@un.org", 
                    "Salqobati@unicef.org", 
                    "salume@unhcr.org", 
                    "salvatore.pantaleo@eeas.europa.eu", 
                    "samachado.rita@gmail.com", 
                    "samal@southcentre.int", 
                    "samantha.bunwaree@un.org", 
                    "samantha.rudick@scalingupnutrition.org", 
                    "samer.altarawneh@wipo.int", 
                    "sami.ghanmi@un.org", 
                    "samiah.figueiredo@wipo.int", 
                    "samira.elgarah@un.org", 
                    "samkova@un.org", 
                    "samuel-brown@gmx.de", 
                    "samuel.gasnault@un.org", 
                    "samuel.pacht@un.org", 
                    "Samuel.Rosenow@unctad.org", 
                    "sanchez11@un.org", 
                    "sancheza@unaids.org", 
                    "sandlund@unhcr.org", 
                    "sandors@unhcr.org", 
                    "sandra.dondenne@wto.org", 
                    "sandra.ramirez@un.org", 
                    "sandra.torrejon@gmail.com", 
                    "sandrapeters@posteo.de", 
                    "sandrene_jackson@hotmail.com", 
                    "sandrine.ammann@wipo.int", 
                    "sandrine.gapihan@un.org", 
                    "sandro.dessi@un.org", 
                    "santiago.fernandezdecordoba@un.org", 
                    "saori.nagahara@mofa.go.jp", 
                    "sara.aboulhosn@un.org", 
                    "sara.blanco@itu.int", 
                    "sara.datturi@un.org", 
                    "sara.kuusi@lasipalatsi.fi", 
                    "sara.lindegren@gov.se", 
                    "Sara.Quinn@scalingupnutrition.org", 
                    "Thomas.Van.Giffen@unctad.org", 
                    "thomastewolde2000@gmail.com", 
                    "THOROMBO@WMO.INT", 
                    "thu@un.org", 
                    "thurn.marion@gmail.com", 
                    "tianya@mofcom.gov.cn", 
                    "tianyi.wang@un.org", 
                    "tiffany.grabski@unctad.org", 
                    "tiffany.hemecker@un.org", 
                    "tihomira.dimova@un.org", 
                    "tilenbaevan@who.int", 
                    "tillef@who.int", 
                    "timos@unops.org", 
                    "timothy.oconnell@un.org", 
                    "tina.puliga@gmail.com", 
                    "tinakalamar@gmail.com", 
                    "Ting.Su@unctad.org", 
                    "tiphaine.di-ruscio@un.org", 
                    "titi.moektijasih@gmail.com", 
                    "tiziana.zugliano@libero.it", 
                    "tkhan@ohchr.org", 
                    "tkhorozyan@ohchr.org", 
                    "tkhrolla@un.org", 
                    "tkrumova@ohchr.org", 
                    "tlee@iom.int", 
                    "tmaehira2015@gmail.com", 
                    "tmatongo@intracen.org", 
                    "tmizutani@wmo.int", 
                    "tmokhduma@ohchr.org", 
                    "tnaydenova@ohchr.org", 
                    "tobias.bednarz@wipo.int", 
                    "TOKI@UNHCR.ORG", 
                    "tomakei1@gmail.com", 
                    "tomas.pons@itu.int", 
                    "tommasogelsomino@gmail.com", 
                    "tommyjeffers@gmail.com", 
                    "tomotaca.nk@gmail.com", 
                    "tonipujades@gmail.com", 
                    "tony.bureau@un.org", 
                    "toshi.aotake@gmail.com", 
                    "tovohery.razakamanana@undp.org", 
                    "tproescholdt@wmo.int", 
                    "trahme@icrc.org", 
                    "travelkev@hotmail.com", 
                    "triblett@gmail.com", 
                    "trine.schmidt@un.org", 
                    "tristan.herrmann@un.org", 
                    "tromel@ilo.org", 
                    "troy@uicc.org", 
                    "ts@ipu.org", 
                    "tsattar@iom.int", 
                    "tsegai.tesfai@un.org", 
                    "tsubasa.enomoto@un.org", 
                    "tsuguki.ishio@un.org", 
                    "tszenderak@icrc.org", 
                    "ttamminen@intracen.org", 
                    "ttanaka@ohchr.org", 
                    "TUDORACHE.DANIELA11@GMAIL.COM", 
                    "tutku.bektas@un.org", 
                    "tvonglaw98@gmail.com", 
                    "twillis@iom.int", 
                    "tzhakshybaeva@icrc.org", 
                    "Ueno@unhcr.org", 
                    "uliana.antipova@un.org", 
                    "uliana@un.org", 
                    "ulises.quero@gmail.com", 
                    "ullmann@un.org", 
                    "una.flanagan@wto.org", 
                    "unas@un.org", 
                    "unmissiongeneva@orderofmalta.int", 
                    "unvmc-hq-eng@un.org", 
                    "uprintern2@ohchr.org", 
                    "uprintern5@ohchr.org", 
                    "ureutz@unhcr.org", 
                    "uri.sharf@me.com", 
                    "URIBE@INTRACEN.ORG", 
                    "urska.cehner@theglobalfund.org", 
                    "urska.ucakar@gov.si", 
                    "Ursula.Moehrle@unctad.org", 
                    "usman.wadir@gmail.com", 
                    "uwe.loewenstein@itu.int", 
                    "vahagn.harutyunyan@un.org", 
                    "vaich@wmo.int", 
                    "valdesj@un.org", 
                    "valencia5@un.org", 
                    "valentina.rivas@unctad.org", 
                    "valentina.shapiro@ifrc.org", 
                    "valeria.caccavo@gmail.com", 
                    "valerie.bertin@un.org", 
                    "valerie.desmontais@un.org", 
                    "VALERIE.VARELA@WSSCC.ORG", 
                    "vanceculbert@gmail.com", 
                    "vane@unicef.org", 
                    "vanek@unhcr.org", 
                    "VANESSA.A.SANTOS@GMAIL.COM", 
                    "vanessa.bauer@un.org", 
                    "Vanessa.McCarthy@unctad.org", 
                    "vanessa.pravecek@wto.org", 
                    "vanessa.tampieri@gmail.com", 
                    "vangulikh@unaids.org", 
                    "vania.etropolska@un.org", 
        ];

        $filt = array_diff($unique_email_address_valid, $sent);
        // dd($unique_email_address_valid, $filt);
        $unique_email_address_valid = collect($filt);

        $unique_email_address_chunked = $unique_email_address_valid->chunk(200);
        foreach ($unique_email_address_chunked as $unique_email_address_chunk) {
            $this->sendBroadcastEmail($unique_email_address_chunk);
        }
        $time_elapsed_secs = microtime(true) - $start;
        // dd($time_elapsed_secs, $unique_email_address_chunked);
        $request->session()->flash('success', 'Broadcast email sent! Error sending to: ' . json_encode($emailError) );
        return redirect()->back();
    }

    /**
     * Send broadcast reminder email to all students EXCEPT students who have already submitted a form
     * @param  Request $request 
     * @return HTML Closure           
     */
    public function sendBroadcastReminder(Request $request)
    {
        // query students who have logged in
        $query_email_addresses = User::where('must_change_password', 0)
            ->where('mailing_list', 1)
            ->select('email')
            ->groupBy('email')
            ->get()
            ->pluck('email');

        $term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Create a valid term.');
            return redirect()->back();
        }

        $selectedTerm = $request->session()->get('Term');
        $queryStudentsAlreadyEnrolled = Preenrolment::where('Term', $selectedTerm)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $queryStudentsAlreadyPlaced = PlacementForm::where('Term', $selectedTerm)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $mergeNotToBeEmailed =  $queryStudentsAlreadyEnrolled->merge($queryStudentsAlreadyPlaced);
        $uniqueEmailAddressNotEmailed = $mergeNotToBeEmailed->unique();

        $differenceInEmails = array_diff($query_email_addresses->toArray(), $uniqueEmailAddressNotEmailed->toArray()); // get difference
        $differenceInEmails = array_unique($differenceInEmails); // remove dupes

        $collectDifferenceEmails = collect($differenceInEmails);

        $query_students_current_year = Repo::where('Term', $term->Term_Code)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $differenceInEmails2 = array_diff($query_students_current_year->toArray(), $uniqueEmailAddressNotEmailed->toArray()); // get difference
        $differenceInEmails2 = array_unique($differenceInEmails2); // remove dupes

        $collectDifferenceEmails2 = collect($differenceInEmails2);

        $merge = $collectDifferenceEmails->merge($collectDifferenceEmails2);

        $unique_email_address = $merge->unique();

        // dd($uniqueEmailAddressNotEmailed, $collectDifferenceEmails, $unique_email_address);

        // $sddextr_email_address = 'allyson.frias@gmail.com';
        $emailError = [];
        $validEmails = [];
        foreach ($unique_email_address as $sddextr_email_address) {
            $my_data = [ 'email' => $sddextr_email_address,];
            $validator = Validator::make($my_data, [
                'email' => 'email',
            ]);
            if ($validator->fails()) {
                $emailError[] = $sddextr_email_address;
            } 
            $validEmails[] = $sddextr_email_address;
        }
        $unique_email_address_valid = $validEmails;
        $unique_email_address_valid = collect($unique_email_address_valid);

        $unique_email_address_chunked = $unique_email_address_valid->chunk(200);
        foreach ($unique_email_address_chunked as $unique_email_address_chunk) {
            $this->sendBroadcastEmail($unique_email_address_chunk);
        }

        $request->session()->flash('success', 'Broadcast reminder email sent! Error sending to: ' . json_encode($emailError) );
        return redirect()->back();
    }


    /**
     * Sends a reminder email ONLY to students who are in a class in the current term but have yet to enrol for the next term
     * @param  Request $request 
     * @return HTML Closure 
     */
    public function sendReminderToCurrentStudents(Request $request)
    {
        $term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        $selectedTerm = $request->session()->get('Term'); 
        // query all students enrolled to current term
        $query_students_current_term = Repo::where('Term', $term->Term_Code)->get();

        $arr1 = [];
        $arr0 = [];
        foreach ($query_students_current_term as $key => $value) {
            $arr0[] = $value->INDEXID;

            $query_not_enrolled_stds = Preenrolment::where('INDEXID', $value->INDEXID)->where('Term', $selectedTerm)->get();
            foreach ($query_not_enrolled_stds as $key2 => $value2) {
                $arr1[] = $value2->INDEXID;
            }
        }

        $arr3 = [];
        foreach ($query_students_current_term as $key5 => $value5) {
            $query_not_enrolled_stds_pl = PlacementForm::where('INDEXID', $value5->INDEXID)->where('Term', $selectedTerm)->get();
            foreach ($query_not_enrolled_stds_pl as $key6 => $value6) {
                $arr3[] = $value6->INDEXID;
            }
        }

        $arr0 = array_unique($arr0); // remove dupes
        $arr1 = array_unique($arr1); // remove dupes
        $arr3 = array_unique($arr3); // remove dupes

        $difference = array_diff($arr0, $arr1); // get difference
        $difference = array_unique($difference); // remove dupes

        $diff = array_diff($difference, $arr3);
        $diff = array_unique($diff);

        $difftest = array_diff($arr1, $arr3);
        $difftest = array_unique($difftest);

        $emailError = [];
        $validEmails = [];
        foreach ($diff as $value3) {
            $query_email_addresses = User::where('indexno', $value3)->get(['email']);
            foreach ($query_email_addresses as $value4) {
                $sddextr_email_address = $value4->email;

                $my_data = [ 'email' => $sddextr_email_address,];
                $validator = Validator::make($my_data, [
                    'email' => 'email',
                ]);
                if ($validator->fails()) {
                    $emailError[] = $sddextr_email_address;
                } 
                $validEmails[] = $sddextr_email_address;
            }
        }

        $unique_email_address_valid = $validEmails;
        $unique_email_address_valid = collect($unique_email_address_valid);

        $unique_email_address_chunked = $unique_email_address_valid->chunk(200);
        foreach ($unique_email_address_chunked as $unique_email_address_chunk) {
            $this->sendBroadcastEmail($unique_email_address_chunk);
        }
        
        $request->session()->flash('success', 'Reminder email sent to ' . count($validEmails) . ' students! Error sending to: ' . json_encode($emailError) );
        return redirect()->back();
    }

    public function sendBroadcastEmail($unique_email_address)
    {
        $baseDelay = Carbon::now();

        $getDelay = cache('_jobs.' . SendBroadcastJob::class, $baseDelay);

        $setDelay = Carbon::parse(
            $getDelay
        )->addSeconds(60);

        // insert data to cache table
        cache([
            '_jobs.' . SendBroadcastJob::class => $setDelay
        ], 5);

        $job = (new SendBroadcastJob($unique_email_address))->delay($setDelay);
        dispatch($job);
    }
}
