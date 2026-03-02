<?php

use App\Http\Controllers\Admin\AttributionController;
use App\Http\Controllers\Admin\BienController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AdminPasswordController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\Client\AchatController;
use App\Http\Controllers\Client\ClientBienController;
use App\Http\Controllers\Client\ClientPanierController;
use App\Http\Controllers\Client\DashboardController as ClientDashboard;
use App\Http\Controllers\Client\FedapayController;
use App\Http\Controllers\Client\PaiementController;
use App\Http\Controllers\Client\PayPalController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Proprietaire\BoutiqueConfigureController;
use App\Http\Controllers\Proprietaire\DashboardController as ProprietaireDashboardController;

use App\Http\Controllers\Livreur\DashboardController as LivreurDashboardController;
use App\Http\Controllers\Proprietaire\ProprietaireBienController;
use App\Http\Controllers\ViewController;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\Client\LivraisonController as ClientLivraisonController;
use App\Http\Controllers\Api\Admin\LivraisonController as AdminLivraisonController;




















/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});


Route::get('/accueil', function () {
    return view('index');
})->name('accueil');

Route::get('/apropos', [ViewController::class, 'apropos']);

Route::get('/catalogue', [ViewController::class, 'catalogue'])->name('catalogue');

Route::get('/gestion-locative', [ViewController::class, 'gestion']);

Route::get('/faq', [ViewController::class, 'faq']);

Route::get('/actualite', [ViewController::class, 'actualite']);

Route::get('/boutiques', [ViewController::class, 'boutiques']);

Route::get('/nos-partenaire', [ViewController::class, 'partenaire']);

Route::get('/produits', [ViewController::class, 'produits']);






// Login ADMIN
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
// Admin : Changement de mot de passe obligatoire dès la première connexion
// Route::get('/admin/change-password', function () {
//     return view('admin.change-password');
// })->name('admin.password.change');

Route::get('/admin/change-password', function () {
    return view('admin.change-password');
})->name('admin.password.change');

//Mise à jour du mot de passe après changement
Route::middleware('auth')->group(function () {
    Route::get('/admin/change-password', [AdminPasswordController::class, 'showForm'])->name('admin.password.change');
    Route::post('/admin/change-password', [AdminPasswordController::class, 'update'])->name('admin.password.update');
    
});



// Auuthentification proprio
Route::middleware(['auth', 'check.password'])->group(function() {
   Route::get('/dashbord', [ProprietaireDashboardController::class, 'index' ]);
});


// Route de connexion propriétaire
Route::get('/proprietaire/login', [LoginController::class, 'showLoginForm'])->name('proprietaire.login');
Route::post('/proprietaire/login', [LoginController::class, 'login'])->name('proprietaire.login.submit');
Route::post('/proprietaire/logout', [LoginController::class, 'logout'])->name('proprietaire.logout');


//Route de connexion livreur
Route::get('/livreur/login', [LoginController::class, 'showLoginFormLivreur'])->name('livreur.login');
Route::post('/livreur/login', [LoginController::class, 'loginLivreur'])->name('livreur.login.submit');
Route::post('/livreur/logout', [LoginController::class, 'logout'])->name('livreur.logout');



//Route de connexion client
Route::get('/client/login', [LoginController::class, 'showClientLoginForm'])->name('client.login');
Route::post('/client/login', [LoginController::class, 'clientLogin'])->name('client.login.submit');
Route::post('/client/logout', [LoginController::class, 'clientLogout'])->name('client.logout');


Route::middleware(['auth'])->group(function() {
    Route::get('/password/change', [PasswordController::class, 'showChangeForm'])
        ->name('password.change.form');

    Route::post('/password/change', [PasswordController::class, 'update'])
        ->name('password.change.update');
});

Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [App\Http\Controllers\Admin\ContactController::class, 'store'])->name('contact.store');

Route::get('/actualites', [PostController::class, 'index'])->name('blog.index');
Route::get('/actualites/recherche', [PostController::class, 'search'])->name('blog.search');
Route::get('/actualites/{slug}', [PostController::class, 'show'])->name('blog.show');


Route::get('/boutiques', [BoutiqueController::class, 'index'])->name('boutiques.index');
Route::get('/boutiques/{slug}', [BoutiqueController::class, 'show'])->name('boutiques.show');


Route::middleware(['auth'])->group(function () {
    // Notifications - Marquer tout comme lu
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])
    ->name('notifications.markAllRead')
    ->middleware('auth');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::resource('/dashboard', DashboardController::class)->only('index');
    
        Route::resource('users', UserController::class)->only(['index', 'create', 'store']);
        Route::resource('biens', BienController::class);
        Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::resource('posts', PostController::class);
        Route::resource('attributions', AttributionController::class)->only(['index','create','store']);
        Route::get('/paiements', [App\Http\Controllers\Admin\PaiementController::class, 'index'])->name('paiements');
        Route::delete('attributions/{attribution}/annuler', [AttributionController::class, 'annuler'])->name('attributions.annuler');

        Route::resource('boutiques', App\Http\Controllers\Admin\BoutiqueController::class);
        Route::patch('boutiques/{boutique}/toggle', [App\Http\Controllers\Admin\BoutiqueController::class, 'toggle'])
            ->name('boutiques.toggle');

        Route::get(
            '/livraisons/{livraison}/tracking',
            [AdminLivraisonController::class, 'tracking']
        )->name('livraisons.tracking');

        
    });
    

    //PROPRIETAIRE
    Route::middleware('role:proprietaire')->prefix('proprietaire')->name('proprietaire.')->group(function () {
        Route::get('/boutique/configuration', [BoutiqueConfigureController::class, 'index'])->name('boutique.configuration');
        Route::put('boutique/configuration/{boutique}', [BoutiqueConfigureController::class, 'update'])->name('boutique.configuration.update');


        // Localisation géographique
        Route::get('/proprietaire/boutique/localisation', [BoutiqueController::class, 'editLocation'])->name('boutique.localisation');
        Route::post('/proprietaire/boutique/localisation',[BoutiqueConfigureController::class, 'updateLocation'])->name('boutique.location.update');


        Route::get('/dashboard', [ProprietaireDashboardController::class, 'index'])->name('dashboard');

        // Biens du propriétaire
        Route::resource('/biens', ProprietaireBienController::class);

        // Les paiements 
        Route::get('/paiements', [App\Http\Controllers\Proprietaire\PaiementController::class, 'index'])->name('paiements');

        // Mes clients
        Route::get('/mes-clients', [ProprietaireDashboardController::class, 'mesClients'])->name('mesclients');

        // Génération du contrat en pdf
        Route::get('/locataires/{id}/contrat', [ProprietaireDashboardController::class, 'contratPDF'])->name('client.contrat');

    });


    // LIVREUR
    Route::middleware('role:livreur')->prefix('livreur')->name('livreur.')->group(function () {
        Route::get('/dashboard', [LivreurDashboardController::class, 'index'])->name('dashboard');
    });

    // CLIENT
    Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {

        Route::get('/dashboard', [ClientDashboard::class, 'index'])->name('dashboard');

        // Initier panier FedaPay + Callback panier FedaPay
        Route::post('/panier/fedapay', [ClientPanierController::class, 'initierFedaPay'])->name('fedapay.panier.initier');
        Route::get('/fedapay/callback', [ClientPanierController::class, 'fedapayCallback'])->name('fedapay.callback');

        // CHOIX DU LIEU DE LIVRAISON
        Route::get('/livraison/choix', [ClientPanierController::class, 'choisir'])->name('livraison.adresse.choix');
        Route::post('/livraisons/store', [ClientBienController::class, 'SaveLivraison'])->name('livraison.store');


        // Paiement panier paypal + Callback panier Paypal
        Route::post('/panier/payer', [PaiementController::class, 'payerPanier'])->name('paypal.panier');
        Route::get('/panier/success', [PaiementController::class, 'successPanier'])->name('paypal.panier.success');
        Route::get('/panier/cancel', [PaiementController::class, 'cancelPanier'])->name('paypal.panier.cancel');


        Route::get('/paypal/callback', [PaiementController::class, 'paypalCallback'])->name('paypal.callback');

        // Paiements
        Route::get('/paiements', [App\Http\Controllers\Client\PaiementController::class, 'index'])->name('paiements');

        // Tracking du livreur
        Route::get(
            '/livraisons/{livraison}/tracking',
            [ClientLivraisonController::class, 'tracking']
        )->name('livraisons.tracking');


        /** UPLOAD PREUVE */
        Route::post('/proof/upload', [ClientBienController::class, 'uploadProof'])->name('proof.upload');

        /** HISTORIQUE */
        Route::get('/achats', [AchatController::class, 'index'])->name('achats');
    });




        // Conversation commune
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        // => '/conversations' pointe vers ConversationController@index => 1.identifie les interlocuteurs, 2.Charge l'historique des discussion et 3.Permet aussi de démarrer une nouvelle discussion
        Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
        // => Démarrer une nouvelle discussion
        Route::get('/conversations/start/{interlocuteurId}', [ConversationController::class, 'start'])->name('conversations.start');
        // La vue partagée de la discussion chez les deux interlocuteurs
        Route::get('/conversations/{id}', [ConversationController::class, 'show'])->name('conversations.show');

        // MessageController@index parcours toutes les conversations et choisis celui correspondant0. Il vérifie aussi si une conversation est permis entre les deux interlocuteur
        Route::get('/conversations/{id}/messages', [MessageController::class, 'index'])->name('messages.index');
        // Enrégistre les conversations
        Route::post('/conversations/{id}/messages', [MessageController::class, 'store'])->name('messages.store');


            // Cette route pour renvoyer le nombre de discussion non lues
        Route::get('/messages/unread-count', [MessageController::class, 'unreadCount'])
            ->name('messages.unread-count');
        
        // Paramètre route commune 
        Route::get('/parametres', [ParametreController::class, 'index'])->name('parametres.index');
        Route::post('/parametres/photo', [ParametreController::class, 'updatePhoto'])->name('parametres.updatePhoto');

        Route::post('/parametres/update-info', [ParametreController::class, 'updateInfo'])
            ->name('parametres.updateInfo');

        Route::post('/parametres/update-password', [ParametreController::class, 'updatePassword'])
            ->name('parametres.updatePassword');

        Route::post('/parametres/update-boutique-logo', [ParametreController::class, 'updateBoutiqueLogo'])
            ->name('parametres.updateBoutiqueLogo');

    
});








Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
