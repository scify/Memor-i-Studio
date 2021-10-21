@extends('layouts.app')
@section('content')

    <div class="row aboutPageRow">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>Details</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body padding-30">
                    <h4>Information about Memor-i Studio</h4>
                    <b>What is Memor-i Studio?</b>
                    <br>
                    <i>Memor-i Studio</i> is a platform that allows anyone without programming skills to create new inclusive electronic games. You can:
                    <br>
                    <ul class="list margin-bottom-20 margin-top-20">
                        <li><i class="fa fa-check" aria-hidden="true"></i> download games for free</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> create new Memor-i games with new sounds and images</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> create variants ('clones') of a game and enrich it with additional images and sounds.</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> securely play against the computer.</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> securely play online with other players or even with your friends.</li>
                    </ul>
                    <b>Who can use Memor-i Studio</b>
                    <br>
                    The games are created through the platform are targeted at people with blindness (audio file), people with visual impairments (black and white image) and sighted (color image).
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>Πληροφορίες</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body padding-30">
                    <h4>Πληροφορίες Memor-i Studio</h4>
                    <b>Τι είναι</b>
                    <br>
                    Το <i>Memor-i Studio</i> είναι μια πλατφόρμα που επιτρέπει τη δημιουργία νέων ηλεκτρονικών παιχνιδιών από τον καθένα χωρίς γνώσεις προγραμματισμού και πληροφορικής. Μπορείτε να:
                    <br>
                    <ul class="list margin-bottom-20 margin-top-20">
                        <li><i class="fa fa-check" aria-hidden="true"></i> κατεβάσετε παιχνίδια δωρεάν</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> δημιουργήσετε νέα παιχνίδια Memor-i με καινούριους ήχους και εικόνες.</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> δημιουργήσετε παραλλαγές (‘κλώνους’) ενός παιχνιδιού και να το εμπλουτίσετε με επιπλέον εικόνες και ήχους.</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> παίξετε με ασφάλεια εναντίον του υπολογιστή.</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> παίξετε Online με ασφάλεια με άλλους παίκτες ή ακόμα και με τους φίλους σας.</li>
                    </ul>
                    <b>Σε ποιους απευθύνεται</b>
                    <br>
                    Τα παιχνίδια που μπορείτε να δημιουργήσετε μέσω της πλατφόρμας απευθύνονται σε ανθρώπους με τυφλότητα (ηχητικό αρχείο), σε ανθρώπους με προβλήματα όρασης (ασπρόμαυρη εικόνα) καθώς και σε βλέποντες (έγχρωμη εικόνα).
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 centeredVertically">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>ABOUT</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body padding-30">
                    Memor-i studio has been created by <a href="http://www.scify.gr/site/en/">Science For You (SciFY)</a>, a Greek not-for-profit organization.

                    The Memor-i studio project has been funded by <a href="http://www.latsis-foundation.org/eng">Public Benefit Foundation John S. Latsis.</a>

                    For more information, click <a href="http://www.scify.gr/site/en/impact-areas-en/assistive-technologies/memorien">here.</a>
                    <div class="row margin-top-30">
                        <div class="col-md-6 text-align-center">
                            <a href="http://www.scify.gr/site/en/">
                                <img loading="lazy" class="col-md-5 centeredVertically"  src="{{asset("/assets/img/scify.jpg")}}" alt="Latsis Logo Image">
                            </a>
                        </div>
                        <div class="col-md-6 text-align-center">
                            <a href="http://www.latsis-foundation.org/eng">
                                <img loading="lazy" class="col-md-6 margin-top-20 centeredVertically" src="{{asset("/assets/img/latsis_logo.jpg")}}" alt="Latsis Logo Image">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row margin-top-20">
        <div class="col-md-6 centeredVertically">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>Disclaimer</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body padding-30 disclaimerText">
                    Tο Memor-i Studio το δημιούργησε η SciFY και το διαθέτει δωρεάν σε όλους, ως εργαλείο ανοιχτού κώδικα (open source). Οποιοσδήποτε μπορεί να δημιουργήσει τα δικά του παιχνίδια, χωρίς να έχει γνώσεις πληροφορικής.
                    Η SciFY φροντίζει, όσο είναι δυνατόν, όλα τα παιχνίδια του Memor-i Studio να είναι σύμφωνα με τους νόμους και τους κανονισμούς. Επίσης δίνει τη δυνατότητα στους χρήστες να καταγγέλλουν παιχνίδια με απρεπές ή παράνομο περιεχόμενο, με την επιλογή "Αναφορά"/"Report".
                    Παρ' όλα αυτά, η SciFY δεν είναι με κανένα τρόπο υπεύθυνη για το περιεχόμενο ή για παραβάσεις του νόμου περί πνευματικής ιδιοκτησίας παιχνιδιών που έχουν δημιουργηθεί από τρίτους.
                </div>
            </div>
        </div>
    </div>
@endsection
