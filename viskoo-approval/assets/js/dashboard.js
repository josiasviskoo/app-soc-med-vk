// data and logic extracted from dashboard.html script

// ─── Data ───
const SAMPLE_IMAGES = [
  'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&h=600&fit=crop',
  'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&h=600&fit=crop',
  'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&h=600&fit=crop',
  'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=600&fit=crop',
  'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=600&fit=crop',
];

const dates = ["Todos", "03 Mar", "05 Mar", "07 Mar", "10 Mar", "12 Mar", "14 Mar", "17 Mar"];

let headlines = window.viskoo_headlines || [
  { id: 1, date: "03 Mar", headline: "Descubra o sabor que transforma o seu dia. Venha viver a experiência Bella Cucina! 🍝✨", platform: "instagram", status: "pending" },
  { id: 2, date: "03 Mar", headline: "Cada prato conta uma história. A sua começa aqui.", platform: "facebook", status: "approved" },
  { id: 3, date: "05 Mar", headline: "Novo menu de verão chegando! Prepare-se para sabores inesquecíveis 🌞🍹", platform: "both", status: "pending" },
  { id: 4, date: "07 Mar", headline: "A melhor happy hour da cidade agora é na Bella Cucina. De seg a sex, das 17h às 20h 🍸", platform: "instagram", status: "rejected" },
  { id: 5, date: "10 Mar", headline: "Sexta é dia de pizza artesanal com massa feita na hora! Reserve sua mesa 🍕", platform: "both", status: "pending" },
  { id: 6, date: "12 Mar", headline: "O brunch dos sonhos te espera todo sábado. De panquecas a ovos beneditinos 🥞", platform: "instagram", status: "pending" },
];

let contents = window.viskoo_contents || [
  { id: 1, date: "03 Mar", caption: "Nosso burger artesanal com blend exclusivo, queijo cheddar derretido e molho da casa. Uma explosão de sabor a cada mordida! 🍔 #BellaCucina #BurgerArtesanal", mediaType: "image", mediaSrc: SAMPLE_IMAGES[0], platform: "instagram", status: "pending", contentFormat: "feed", carouselImages: [] },
  { id: 2, date: "05 Mar", caption: "Aquele café perfeito pra começar o dia com energia. Nosso barista prepara com carinho cada xícara ☕ #CaféEspecial", mediaType: "image", mediaSrc: SAMPLE_IMAGES[1], platform: "both", status: "approved", contentFormat: "stories", carouselImages: [] },
  { id: 3, date: "07 Mar", caption: "Conheça nossa nova linha de produtos artesanais. Ingredientes selecionados para levar o melhor até você 🧴✨", mediaType: "image", mediaSrc: SAMPLE_IMAGES[2], platform: "facebook", status: "pending", contentFormat: "carousel", carouselImages: [SAMPLE_IMAGES[2], SAMPLE_IMAGES[0], SAMPLE_IMAGES[1]] },
  { id: 4, date: "10 Mar", caption: "Vista aérea do nosso terraço especial! Reserve para uma noite inesquecível sob as estrelas ✨🍷 #NightOut", mediaType: "video", mediaSrc: SAMPLE_IMAGES[3], platform: "instagram", status: "pending", contentFormat: "reels", carouselImages: [] },
  { id: 5, date: "12 Mar", caption: "Sexta-feira combina com pizza artesanal! Massa fermentada por 48h, molho de tomate San Marzano 🍕", mediaType: "image", mediaSrc: SAMPLE_IMAGES[4], platform: "both", status: "rejected", contentFormat: "feed", carouselImages: [] },
];

let activeStage = "content";
let activeDate = "Todos";
let activeStatus = "all";

// rest of the script ...

// ─── Utility icons and helpers (copy the rest of original JS) --

const icons = {
  check: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>',
  x: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>',
  comment: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v8l-4-4H7a2 2 0 01-2-2V10a2 2 0 012-2h2"/></svg>',
  checkCircle: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>',
  clock: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>',
  calendar: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3M16 7V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
  image: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18V3H3zm5 11l3-4 2 3 3-4 2 3"/></svg>',
  film: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4z"/><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M16 17v4M8 17v4"/></svg>',
  plus: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 6v12M6 12h12"/></svg>',
  play: '<svg class="icon" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 3l14 9-14 9V3z"/></svg>'
};

function platformIcon(p) {
  switch(p) {
    case 'instagram': return icons.checkCircle + ' Insta';
    case 'facebook': return icons.checkCircle + ' FB';
    case 'both': return icons.checkCircle + ' IG/FB';
    default: return '';
  }
}

const formatMeta = {
  feed: { label: 'Feed', icon: icons.image, badgeClass: 'badge-primary-bg' },
  stories: { label: 'Stories', icon: icons.checkCircle, badgeClass: 'badge-accent' },
  carousel: { label: 'Carrossel', icon: icons.image, badgeClass: 'badge-accent' },
  reels: { label: 'Reels', icon: icons.film, badgeClass: 'badge-accent' },
};

// dialog, toast, counters, render functions, etc.

let dialogCallback = null;
function showDialog(title, desc, cb) {
  document.getElementById('dialog-title').textContent = title;
  document.getElementById('dialog-desc').textContent = desc;
  document.getElementById('confirm-dialog').style.display = 'flex';
  dialogCallback = cb;
  document.getElementById('dialog-confirm-btn').onclick = () => { closeDialog(); if (dialogCallback) dialogCallback(); };
}
function closeDialog() { document.getElementById('confirm-dialog').style.display = 'none'; dialogCallback = null; }

function updateCounts() {
  const all = [...headlines, ...contents];
  document.getElementById('count-pending').textContent = all.filter(i => i.status === 'pending').length;
  document.getElementById('count-approved').textContent = all.filter(i => i.status === 'approved').length;
  document.getElementById('count-rejected').textContent = all.filter(i => i.status === 'rejected').length;
}

// stage toggles
function setStage(s) {
  activeStage = s;
  document.querySelectorAll('.stage-btn').forEach(b => {
    b.classList.toggle('active', b.dataset.stage === s);
    b.classList.toggle('gradient-primary', b.dataset.stage === s);
  });
  render();
}

function renderTimeline() {
  const el = document.getElementById('timeline');
  el.innerHTML = dates.map(d =>
    `<button class="timeline-btn ${activeDate === d ? 'active gradient-primary' : ''}" onclick="setDate('${d}')">${d}</button>`
  ).join('');
}
function setDate(d) { activeDate = d; renderTimeline(); render(); }

function renderStatusFilter() {
  const filters = [
    { value: 'all', label: 'Todos', dot: '' },
    { value: 'pending', label: 'Pendentes', dot: 'dot-warning' },
    { value: 'approved', label: 'Aprovados', dot: 'dot-success' },
    { value: 'rejected', label: 'Reprovados', dot: 'dot-primary' },
  ];
  const el = document.getElementById('status-filter');
  el.innerHTML = filters.map(f =>
    `<button class="filter-btn ${activeStatus === f.value ? 'active' : ''}" onclick="setStatus('${f.value}')">
          ${f.dot ? `<span class="filter-dot ${f.dot}"></span>` : ''}${f.label}
        </button>`
  ).join('');
}
function setStatus(s) { activeStatus = s; renderStatusFilter(); render(); }

function filterItems(items) {
  let r = activeDate === 'Todos' ? items : items.filter(i => i.date === activeDate);
  if (activeStatus !== 'all') r = r.filter(i => i.status === activeStatus);
  return r;
}

function renderContentCard(c) {
  const fmt = formatMeta[c.contentFormat];
  const isPortrait = c.contentFormat === 'stories' || c.contentFormat === 'reels';
  const statusBorderClass = c.status === 'approved' ? 'border-success' : c.status === 'rejected' ? 'border-primary' : '';
  const statusLabel = c.status === 'approved' ? 'Aprovado' : c.status === 'rejected' ? 'Reprovado' : 'Pendente';
  const statusClass = 'status-' + c.status;

  let mediaHtml = `<img src="${c.mediaSrc}" alt="Post content" loading="lazy" />`;
  if (c.contentFormat === 'carousel' && c.carouselImages.length > 1) {
    mediaHtml = `<img src="${c.carouselImages[0]}" alt="Slide 1" loading="lazy" />
          <div class="carousel-dots">${c.carouselImages.map((_, i) => `<span class="carousel-dot ${i === 0 ? 'active' : ''}"></span>`).join('')}</div>`;
  }

  let videoOverlay = c.mediaType === 'video' ? `<div class="play-overlay"><div class="play-btn gradient-primary shadow-glow">${icons.play}</div></div>` : '';

  let pendingActions = '';
  if (c.status === 'pending') {
    pendingActions = `
          <div class="timer-row">
            <span class="icon">${icons.clock}</span>
            <div class="timer-bar-bg">
              <div class="timer-labels"><span class="timer-label">Auto-aprovação</span><span class="timer-label timer-mono">23:59:59</span></div>
              <div class="timer-track"><div class="timer-fill" style="width:98%"></div></div>
            </div>
          </div>
          <div style="display:flex;gap:0.5rem;margin-bottom:0.25rem">
            <button class="suggest-date-btn">${icons.calendar} Sugerir data</button>
          </div>
          <div class="action-row">
            <button class="btn-approve" onclick="approveContent(${c.id})">${icons.check} Aprovar</button>
            <button class="btn-reject" onclick="toggleCommentContent(${c.id})">${icons.x} Reprovar</button>
            <button class="btn-comment" onclick="toggleCommentContent(${c.id})">${icons.comment}</button>
          </div>
          <div id="comment-content-${c.id}" class="comment-box" style="display:none">
            <textarea id="comment-text-content-${c.id}" placeholder="Deixe seu comentário..." rows="2"></textarea>
            <button class="btn-send-feedback gradient-primary" onclick="rejectContent(${c.id})">Enviar feedback</button>
          </div>`;
  }

  return `<div class="content-card ${statusBorderClass}">
        <div class="card-media ${isPortrait ? 'portrait' : 'square'}">
          ${mediaHtml}${videoOverlay}
          <div class="card-badges">
            <span class="badge badge-glass">${c.mediaType === 'video' ? icons.film + ' Vídeo' : icons.image + ' Imagem'}</span>
            <span class="badge ${fmt.badgeClass}">${fmt.icon} ${fmt.label}</span>
          </div>
          <span class="card-status ${statusClass}">${statusLabel}</span>
        </div>
        <div class="card-body">
          <div class="card-meta"><span>${c.date}</span><span class="dot">•</span>${platformIcon(c.platform)}</div>
          <p class="card-caption line-clamp-3">${c.caption}</p>
          ${pendingActions}
          <button class="btn-delete" onclick="deleteContent(${c.id})">${icons.x} Excluir conteúdo</button>
        </div>
      </div>`;
}

function renderHeadlineCard(h) {
  const statusBorderClass = h.status === 'approved' ? 'border-success' : h.status === 'rejected' ? 'border-primary' : '';
  const statusLabel = h.status === 'approved' ? 'Aprovado' : h.status === 'rejected' ? 'Reprovado' : 'Pendente';
  const statusClass = 'hs-' + h.status;

  let pendingActions = '';
  if (h.status === 'pending') {
    pendingActions = `
          <div style="display:flex;gap:0.5rem;margin-bottom:0.5rem">
            <button class="suggest-date-btn">${icons.calendar} Sugerir data</button>
          </div>
          <div class="action-row">
            <button class="btn-approve" onclick="approveHeadline(${h.id})">${icons.check} Aprovar</button>
            <button class="btn-reject" onclick="toggleCommentHeadline(${h.id})">${icons.x} Reprovar</button>
            <button class="btn-comment" onclick="toggleCommentHeadline(${h.id})">${icons.comment}</button>
          </div>
          <div id="comment-headline-${h.id}" class="comment-box" style="display:none">
            <textarea id="comment-text-headline-${h.id}" placeholder="Deixe seu comentário..." rows="2"></textarea>
            <button class="btn-send-feedback gradient-primary" onclick="rejectHeadline(${h.id})">Enviar feedback</button>
          </div>`;
  }

  return `<div class="headline-card ${statusBorderClass}">
        <div class="headline-meta">
          <div class="headline-meta-left"><span>${h.date}</span><span class="dot" style="color:hsla(240,5%,55%,0.5)">•</span>${platformIcon(h.platform)}</div>
          <span class="headline-status ${statusClass}">${statusLabel}</span>
        </div>
        <p class="headline-text">"${h.headline}"</p>
        ${pendingActions}
        <button class="btn-delete" onclick="deleteHeadline(${h.id})">${icons.x} Excluir headline</button>
      </div>`;
}

function approveContent(id) {
  jQuery.post(viskoo_vars.ajax_url, {
    action: 'viskoo_toggle_status',
    post_id: id,
    status: 'approved',
    nonce: viskoo_vars.nonce
  }, function(res) {
    if (res.success) {
      contents = contents.map(c => c.id === id ? {...c, status: 'approved'} : c);
      updateCounts(); render();
      showToast('✅ Conteúdo aprovado', 'O conteúdo foi aprovado com sucesso.');
    } else {
      showToast('⚠️ Erro', 'Não foi possível aprovar o conteúdo.');
    }
  });
}
function rejectContent(id) {
  const comment = document.getElementById('comment-text-content-' + id)?.value || '';
  jQuery.post(viskoo_vars.ajax_url, {
    action: 'viskoo_toggle_status',
    post_id: id,
    status: 'rejected',
    nonce: viskoo_vars.nonce
  });
  if (comment) {
    jQuery.post(viskoo_vars.ajax_url, {
      action: 'viskoo_save_comment',
      post_id: id,
      comment: comment,
      nonce: viskoo_vars.nonce
    });
  }
  contents = contents.map(c => c.id === id ? {...c, status: 'rejected', rejectionComment: comment} : c);
  updateCounts(); render();
  showToast('❌ Conteúdo reprovado', comment ? 'Feedback enviado à agência.' : 'O conteúdo foi reprovado.');
}
function deleteContent(id) { showDialog('Excluir conteúdo?', 'Essa ação não pode ser desfeita. O conteúdo será removido permanentemente.', () => { 
    // could call ajax to delete post
    contents = contents.filter(c => c.id !== id); updateCounts(); render(); showToast('🗑️ Conteúdo excluído', 'O conteúdo foi removido.');
  }); }
function toggleCommentContent(id) { const el = document.getElementById('comment-content-' + id); if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none'; }

function approveHeadline(id) {
  jQuery.post(viskoo_vars.ajax_url, {
    action: 'viskoo_toggle_status',
    post_id: id,
    status: 'approved',
    nonce: viskoo_vars.nonce
  }, function(res) {
    if (res.success) {
      headlines = headlines.map(h => h.id === id ? {...h, status: 'approved'} : h);
      updateCounts(); render();
      showToast('✅ Headline aprovada', 'A headline foi aprovada com sucesso.');
    } else {
      showToast('⚠️ Erro', 'Não foi possível aprovar a headline.');
    }
  });
}
function rejectHeadline(id) {
  const comment = document.getElementById('comment-text-headline-' + id)?.value || '';
  jQuery.post(viskoo_vars.ajax_url, {
    action: 'viskoo_toggle_status',
    post_id: id,
    status: 'rejected',
    nonce: viskoo_vars.nonce
  });
  if (comment) {
    jQuery.post(viskoo_vars.ajax_url, {
      action: 'viskoo_save_comment',
      post_id: id,
      comment: comment,
      nonce: viskoo_vars.nonce
    });
  }
  headlines = headlines.map(h => h.id === id ? {...h, status: 'rejected', rejectionComment: comment} : h);
  updateCounts(); render();
  showToast('❌ Headline reprovada', comment ? 'Feedback enviado à agência.' : 'A headline foi reprovada.');
}
function deleteHeadline(id) { showDialog('Excluir headline?', 'Essa ação não pode ser desfeita. A headline será removida permanentemente.', () => { headlines = headlines.filter(h => h.id !== id); updateCounts(); render(); showToast('🗑️ Headline excluída', 'A headline foi removida.'); }); }
function toggleCommentHeadline(id) { const el = document.getElementById('comment-headline-' + id); if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none'; }

// ─── Main Render ───
function render() {
  const area = document.getElementById('content-area');
  if (activeStage === 'content') {
    const items = filterItems(contents);
    area.innerHTML = `
          <div class="add-card" onclick="showToast('ℹ️ Em breve', 'Funcionalidade de criação de conteúdo.')">
            <div class="plus-circle"><span class="plus-icon">${icons.plus}</span></div>
            <div><div class="add-label">Novo Conteúdo</div><div class="add-sub">Post, stories ou reels</div></div>
          </div>
          ${items.length ? `<div class="masonry">${items.map(renderContentCard).join('')}</div>` : '<div class="empty-state">Nenhum conteúdo para esta data.</div>'}`;
  } else {
    const items = filterItems(headlines);
    area.innerHTML = `
          <div class="add-card" onclick="showToast('ℹ️ Em breve', 'Funcionalidade de criação de headline.')">
            <div class="plus-circle"><span class="plus-icon">${icons.plus}</span></div>
            <div><div class="add-label">Nova Headline</div><div class="add-sub">Adicionar título para aprovação</div></div>
          </div>
          ${items.length ? `<div class="card-grid">${items.map(renderHeadlineCard).join('')}</div>` : '<div class="empty-state">Nenhum conteúdo para esta data.</div>'}`;
  }
}

// ─── Init ───
renderTimeline();
renderStatusFilter();
updateCounts();
render();【TRUNCATED】