window.showSuccess = function () {
  const overlay = document.getElementById('successOverlay');
  const tick    = document.getElementById('successTick');
  const canvas  = document.getElementById('confettiCanvas');

  if (!overlay || !tick || !canvas) return;

  // Affiche l'overlay
  overlay.classList.remove('hidden');
  overlay.classList.add('flex');

  // Affiche le tick
  tick.classList.remove('opacity-0');
  tick.classList.add('scale-100');

  // Confettis — explosion bien centrée
  if (typeof confetti !== 'undefined') {
    const fire = confetti.create(canvas, { resize: true, useWorker: true });

    // Explosion centrée (en bas du ✔️)
    fire({
      particleCount: 180,
      spread: 100,
      startVelocity: 45,
      scalar: 1.2,
      ticks: 300, // durée plus longue (~3s)
      origin: { x: 0.5, y: 0.5 } // parfaitement centré
    });
  }

  // Cache l’overlay après 3 secondes
  setTimeout(() => {
    overlay.classList.remove('flex');
    overlay.classList.add('hidden');

    tick.classList.remove('scale-100');
    tick.classList.add('opacity-0');
  }, 3000);
};





