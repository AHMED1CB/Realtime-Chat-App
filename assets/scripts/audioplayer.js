document.addEventListener("DOMContentLoaded", function() {
     const players = document.querySelectorAll(".audio-player");
     
     players.forEach((player) => {
          const audio = player.querySelector("audio");
          const playButton = player.querySelector(".play-button");
          const progressBar = player.querySelector(".progress-bar");
          const progress = player.querySelector(".progress");
          const time = player.querySelector(".time");

          playButton.addEventListener("click", () => {
              if (audio.paused) {
                  audio.play();
                  playButton.innerHTML = `<i class="ph ph-pause-fill"></i>`;
              } else {
                  audio.pause();
                  playButton.innerHTML = `<i class="ph ph-play-fill"></i>`; 
              }
          });
     
          audio.addEventListener("timeupdate", () => {
              const percent = (audio.currentTime / audio.duration) * 100;
              progress.style.width = percent + "%";
              time.textContent = formatTime(audio.currentTime);
              if (audio.ended){
                  playButton.innerHTML = `<i class="ph ph-play-fill"></i>`; 

              }
          });
     
          progressBar.addEventListener("click", (e) => {
              const rect = progressBar.getBoundingClientRect();
              const offsetX = e.clientX - rect.left;
              const newTime = (offsetX / rect.width) * audio.duration;
              audio.currentTime = newTime;
          });
     
          function formatTime(seconds) {
              const minutes = Math.floor(seconds / 60);
              const secs = Math.floor(seconds % 60);
              return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
          }
     })


 });

