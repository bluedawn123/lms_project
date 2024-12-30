    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>


    <script>
    // 대시보드 버튼 클릭 시 index.php로 이동
    document.addEventListener("DOMContentLoaded", ()=>{
    const dashboardButton = document.getElementById("dashboardButton");

    dashboardButton.addEventListener("click", (e)=>{
      e.preventDefault();
      dashboardButton.classList.remove("collapsed");

        setTimeout(() => {
            window.location.href = "http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/index.php";
        }, 500);
        });
    });
    </script>
  </body>

</html>
    <?php
    $mysqli->close();
    ?>