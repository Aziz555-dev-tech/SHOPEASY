@extends('layouts.app')

@section('title', 'FAQ')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="{{ asset('css/actualite.css') }}">
<link rel="stylesheet" href="{{ asset('css/styleIndex.css') }}">


{{-- CSS PERSONNALISÉ --}}
<style>
    .faq-section {
        background: #f9fcff;
    }

    .faq-container {
        max-width: 900px;
        margin: 0 auto;
        animation: fadeInUp 0.8s ease both;
    }

    .faq-item {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0,80,120,0.12);
        margin-bottom: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 25px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .faq-header:hover {
        background: #e7f5ff;
    }

    .faq-header h5 {
        flex: 1;
        margin-left: 12px;
        color: var(--dark-blue);
        font-weight: 600;
    }

    /* --------- CERCLE ICON CENTRÉ ✔️ --------- */
    .faq-header .circle {
        width: 45px;
        height: 45px;
        background: var(--dark-blue);
        color: white;
        border-radius: 50%;
        display: flex;               /* ✔️ centre horizontal */
        align-items: center;         /* ✔️ centre vertical */
        justify-content: center;     /* ✔️ centre total */
        font-size: 20px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }

    .toggle-icon {
        font-size: 26px;
        color: var(--dark-blue);
        font-weight: bold;
        transition: transform 0.3s ease;
    }

    .faq-item.active .toggle-icon {
        transform: rotate(180deg);
    }

    .faq-body {
        max-height: 0;
        overflow: hidden;
        padding: 0 25px;
        background: #fcfdff;
        border-top: 1px solid #e1ecf3;
        transition: all 0.4s ease;
    }

    .faq-body p {
        color: #333;
        font-size: 15px;
    }

    .faq-item.active .faq-body {
        max-height: 700px;
        padding: 20px 25px 25px;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>


@section('content')
<section class="faq-section py-5">
  <div class="container">
      <h2 class="text-center fw-bold mb-5" style="color: var(--dark-blue);">
          Conditions d’accès à nos services
      </h2>

      <div class="faq-container">

          <!-- FAQ 1 -->
          <div class="faq-item">
              <div class="faq-header">
                  <div class="circle"><i class="bi bi-house-add"></i></div>
                  <h5>Comment créer ma boutique sur ShopEasy ?</h5>
                  <div class="toggle-icon">+</div>
              </div>
              <div class="faq-body">
                  <p>
                      Créer votre boutique est simple : inscrivez-vous gratuitement, personnalisez-la avec votre logo et vos couleurs, ajoutez vos produits avec photos et descriptions, puis publiez ! Le tout en moins de 10 minutes.
                  </p>
              </div>
          </div>

          <!-- FAQ 2 -->
          <div class="faq-item">
              <div class="faq-header">
                  <div class="circle"><i class="bi bi-credit-card-2-back"></i></div>
                  <h5>Quels sont les moyens de paiement acceptés ?</h5>
                  <div class="toggle-icon">+</div>
              </div>
              <div class="faq-body">
                  <p>
                      ShopEasy accepte les paiements via Mobile Money (MTN Money, Moov Money, Orange Money), cartes bancaires et virements. Tous les paiements sont sécurisés et cryptés.
                  </p>
              </div>
          </div>

          <!-- FAQ 3 -->
          <div class="faq-item">
              <div class="faq-header">
                  <div class="circle"><i class="bi bi-globe-europe-africa"></i></div>
                  <h5>Puis-je vendre dans plusieurs pays africains ?</h5>
                  <div class="toggle-icon">+</div>
              </div>
              <div class="faq-body">
                  <p>
                      Oui ! ShopEasy est disponible dans 54 pays africains. Vous pouvez configurer les zones de livraison et les devises selon vos besoins.
                  </p>
              </div>
          </div>

          <!-- FAQ 4 -->
          <div class="faq-item">
              <div class="faq-header">
                  <div class="circle"><i class="bi bi-cash-coin"></i></div>
                  <h5>Y a-t-il des frais de transaction ?</h5>
                  <div class="toggle-icon">+</div>
              </div>
              <div class="faq-body">
                  <p>
                      Le plan Gratuit inclut une commission de 5% par vente. Les plans Standard et Premium proposent des frais réduits à 2.5% et 1.5% respectivement. Aucun frais caché.
                  </p>
              </div>
          </div>

          <!-- FAQ 5 -->
          <div class="faq-item">
              <div class="faq-header">
                  <div class="circle"><i class="bi bi-truck"></i></div>
                  <h5>Comment fonctionne la livraison ?</h5>
                  <div class="toggle-icon">+</div>
              </div>
              <div class="faq-body">
                  <p>
                      Vous pouvez gérer la livraison vous-même ou utiliser nos partenaires logistiques intégrés. Configurez vos zones de livraison, tarifs et délais depuis votre dashboard.
                  </p>
              </div>
          </div>

          <!-- FAQ 6 -->
          <div class="faq-item">
              <div class="faq-header">
                  <div class="circle"><i class="bi bi-chat-right-text"></i></div>
                  <h5>Le support client est-il disponible en français ?</h5>
                  <div class="toggle-icon">+</div>
              </div>
              <div class="faq-body">
                  <p>
                      Absolument ! Notre support est disponible en français, anglais et plusieurs langues africaines. Contactez-nous par chat, email ou téléphone 24h/24 et 7j/7.
                  </p>
              </div>
          </div>

      </div>
  </div>
</section>

{{-- JS FAQ --}}
<script>
    document.querySelectorAll('.faq-header').forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const icon = header.querySelector('.toggle-icon');

            item.classList.toggle('active');
            icon.textContent = item.classList.contains('active') ? '−' : '+';
        });
    });
</script>
@endsection
