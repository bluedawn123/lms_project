<?php
$title = '카테고리 관리';
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/lecture.css\" rel=\"stylesheet\">";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

if (!isset($_SESSION['AUID'])) {
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}


$sql = "SELECT * FROM lecture_category WHERE step = 1";
$result = $mysqli->query($sql);
while ($data = $result->fetch_object()) { //조회된 값들 마다 할일, 값이 있으면 $data할당
  $cate[] = $data; //$cate1배열에 $data할당
}


$search = '';

$plat = $_GET['plat'] ?? '';
$search_keyword = $_GET['search_keyword'] ?? '';


if ($plat) {
  $search .= " and (code LIKE '$plat')";
}
if ($search_keyword) {
  $search .= " and (name LIKE '%$search_keyword%')";
}
//데이터의 개수 조회
$page_sql = "SELECT COUNT(*) AS cnt FROM lecture_category WHERE step = 3 AND 1=1 $search";
$page_result = $mysqli->query($page_sql);
$page_data = $page_result->fetch_assoc();
$row_num = $page_data['cnt'];

//페이지네이션 
if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

$length = 10;
$start_num = ($page - 1) * $length;
$block_ct = 5;
$block_num = ceil($page / $block_ct); //$page1/5 0.2 = 1

$block_start = (($block_num - 1) * $block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

$total_page = ceil($row_num / $length); //총75개 10개씩, 8
$total_block = ceil($total_page / $block_ct);

if ($block_end > $total_page) $block_end = $total_page;



// $cate_sql = "SELECT * FROM lecture_category WHERE step = 2 ";
// $cate_result = $mysqli->query($cate_sql) ;
// while($data = $cate_result->fetch_object()){ //조회된 값들 마다 할일, 값이 있으면 $data할당
//   $cateArr[]= $data; //$cate1배열에 $data할당
// }
$html = '';
$list = array();
$list_sql = "SELECT * FROM lecture_category WHERE step = 3 AND 1=1 $search ORDER BY lcid LIMIT $start_num, $length";
$list_result = $mysqli->query($list_sql);
while ($list_data = $list_result->fetch_object()) { //조회된 값들 마다 할일, 값이 있으면 $data할당
  $list[] = $list_data; //$cate1배열에 $data할당
}

if (count($list) > 0) {
  $i = 1;
  foreach ($list as $list) {
    $pcode_name_sql = "SELECT name FROM lecture_category WHERE code = '{$list->pcode}' AND pcode = '{$list->ppcode}'";
    $ppcode_name_sql = "SELECT name FROM lecture_category WHERE code = '{$list->ppcode}'";

    $pcode_result = $mysqli->query($pcode_name_sql);
    $ppcode_result = $mysqli->query($ppcode_name_sql);

    $pcode_name = ($pcode_result && $pcode_result->num_rows > 0) ? $pcode_result->fetch_object()->name : "Unknown";
    $ppcode_name = ($ppcode_result && $ppcode_result->num_rows > 0) ? $ppcode_result->fetch_object()->name : "Unknown";

    $html .= "<tr class=\"border-bottom border-secondary-subtitle\">
        <th >{$i}</th>
        <td>{$ppcode_name}</td>
        <td>{$pcode_name}</td>
        <td>{$list->name}</td>
        <td><a href=\"#\" data-bs-toggle=\"modal\" data-bs-target=\"#editModal{$list->lcid}\">
                <img src=\"../../img/icon-img/Edit.svg\" id=\"edit{$list->lcid}\" width=\"20\">
            </a>
        </td>
      </tr>
      <div class=\"modal edit fade\" id=\"editModal{$list->lcid}\" tabindex=\"-1\" aria-labelledby=\"editModalLabel{$list->lcid}\" aria-hidden=\"true\">
        <div class=\"modal-dialog modal-dialog-centered\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <h5 class=\"modal-title\" id=\"editModalLabel{$list->lcid}\">카테고리 수정</h5>
                    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                </div>
                <div class=\"modal-body\">
                    <form id=\"editForm{$list->lcid}\" data-lcid=\"{$list->lcid}\">
                        <div class=\"d-flex mb-3 gap-3\">
                        <input type=\"text\" class=\"form-control\" value=\"{$ppcode_name}\" disabled>
                        <input type=\"text\" class=\"form-control\" value=\"{$pcode_name}\" disabled>
                        </div>
                        <div class=\" mb-3\">
                            <input type=\"text\" class=\"form-control\" id=\"editName{$list->lcid}\" value=\"{$list->name}\">
                        </div>
                        <div class=\"d-flex justify-content-end gap-3\">
                          <button type=\"button\" class=\"btn btn-primary cate_edit\" data-id=\"{$list->lcid}\">수정</button>
                          <button type=\"button\" class=\"btn btn-danger cate_del\" data-id=\"{$list->lcid}\">삭제</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>";
    $i++;
  }
}
?>

<div class="container">
  <div class="row d-flex  category">
    <div class="col-3 mb-5 text-center">
      <h5>Platforms</h5>
      <div class="d-flex gap-2 mt-4" id="plat">
        <select class="form-select plat" name="platforms">
          <option value="" selected>Platforms</option>
          <?php
          if (!empty($cate)) {
            foreach ($cate as $plat) {
          ?>
              <option value="<?= $plat->code; ?>"><?= $plat->name; ?></option>
          <?php
            }
          }
          ?>
        </select>
        <button class=" btn btn-primary" data-bs-toggle="modal" data-bs-target="#platformsModal" id="platforms_btn">등록</button>
      </div>
    </div>
    <div class="col-3 mb-5 text-center">
      <h5>Development</h5>
      <div class="d-flex gap-2 mt-4" id="dev">
        <select class="form-select dev" name="development">
          <option value="" selected>Development</option>
          <!-- <option value="B0001">Front-End</option> -->
        </select>
        <button class=" btn btn-primary" data-bs-toggle="modal" data-bs-target="#developmentModal" id="development_btn">등록</button>
      </div>
    </div>
    <div class="col-3 mb-5 text-center">
      <h5>Technologies</h5>
      <div class="d-flex gap-2 mt-4" id="tech">
        <select class="form-select tech" name="technologies">
          <option value="" selected>Technologies</option>
          <!-- <option value="C0001">React</option> -->
        </select>
        <button class=" btn btn-primary " data-bs-toggle="modal" data-bs-target="#technologiesModal" id="technologies_btn">등록</button>
      </div>
    </div>
    <form class="col-3 mb-5 d-flex align-items-end gap-2">
      <input type="hidden" name="plat" value="">
      <input type="hidden" name="dev" value="">
      <input type="hidden" name="tech" value="">
      <input type="text" name="search_keyword" class="form-control ">
      <button class=" btn btn-secondary ">검색</button>
    </form>
  </div>
  <table class="table table-hover text-center">
    <thead>
      <tr class="border-bottom border-secondary-subtitle thline">
        <th scope="col">No</th>
        <th scope="col">Platforms</th>
        <th scope="col">Development</th>
        <th scope="col">Technologies</th>
        <th scope="col">Edit</th>
      </tr>
    </thead>
    <tbody>
      <?= $html; ?>
    </tbody>
  </table>
</div>
<nav aria-label="Page navigation">
  <ul class="pagination d-flex justify-content-center">
    <?php
    if ($block_num > 1) {
      $prev = $block_start - $block_ct;
      echo "<li class=\"page-item\"><a class=\"page-link\" href=\"category_list.php?search_keyword={$search_keyword}&page={$prev}\"><img src=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/img/icon-img/CaretLeft.svg\" alt=\"페이지네이션 prev\"></a></li>";
    }
    ?>
    <?php
    for ($i = $block_start; $i <= $block_end; $i++) {
      // if($page == $i) {$active = 'active';} else {$active = '';}
      $page == $i ? $active = 'active' : $active = '';
    ?>
      <li class="page-item <?= $active; ?>"><a class="page-link" href="category_list.php?search_keyword=<?= $search_keyword; ?>&page=<?= $i; ?>"><?= $i; ?></a></li>
    <?php
    }
    $next = $block_end + 1;
    if ($total_block >  $block_num) {
    ?>
      <li class="page-item"><a class="page-link" href="category_list.php?search_keyword=<?= $search_keyword; ?>&page=<?= $next; ?>"><img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/CaretRight.svg" alt="페이지네이션 next"></a></li>
    <?php
    }
    ?>
  </ul>
</nav>
<div class="modal fade" id="platformsModal" tabindex="1001" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Platforms</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" data-step="1" class="platform">
          <div class="row d-flex justify-content-center">
            <div class="d-flex justify-content-center gap-2 w-100">
              <input type="text" name="platform" id="platform" class="form-control w-75">
              <button class=" btn btn-primary ">등록</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="developmentModal" tabindex="1001" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Development</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" data-step="2" class="development">
          <div class="row d-flex flex-column gap-3">
            <div>
              <select class="form-select w-50" name="platforms" id="pcode2">
                <option value="" selected>Platforms</option>
                <?php
                if (!empty($cate)) {
                  foreach ($cate as $plat) {
                ?>
                    <option value="<?= $plat->code; ?>"><?= $plat->name; ?></option>
                <?php
                  }
                }
                ?>
              </select>
            </div>
            <div class="d-flex gap-2 w-100">
              <input type="text" id="development" class="form-control w-75">
              <button class=" btn btn-primary ">등록</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="technologiesModal" tabindex="1001" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Technologies</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" data-step="3" class="technologies">
          <div class="row d-flex d-flex flex-column gap-3">
            <div class="d-flex gap-2 w-100">
              <select class="form-select flex-fill plats" name="platforms" id="pcode2">
                <option value="" selected>Platforms</option>
                <?php
                if (!empty($cate)) {
                  foreach ($cate as $plat) {
                ?>
                    <option value="<?= $plat->code; ?>"><?= $plat->name; ?></option>
                <?php
                  }
                }
                ?>
              </select>
              <select class="form-select flex-fill devs" name="development" id="pcode3">

                <option value="" selected>Development</option>
              </select>
            </div>
            <div class="d-flex justify-content-center gap-2 w-100">
              <input type="text" id="technologies" class="form-control w-75">
              <button class=" btn btn-primary ">등록</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editform" tabindex="1001" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Technologies</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" data-step="3" class="technologies">
          <div class="row d-flex d-flex flex-column gap-3">
            <div class="d-flex gap-2 w-100">
              <select class="form-select flex-fill plats" name="platforms" id="pcode2">
                <option value="" selected>Platforms</option>
                <?php
                if (!empty($cate)) {
                  foreach ($cate as $plat) {
                ?>
                    <option value="<?= $plat->code; ?>"><?= $plat->name; ?></option>
                <?php
                  }
                }
                ?>
              </select>
              <select class="form-select flex-fill devs" name="development" id="pcode3">

                <option value="" selected>Development</option>
              </select>
            </div>
            <div class="d-flex justify-content-center gap-2 w-100">
              <input type="text" id="technologies" class="form-control w-75">
              <button class=" btn btn-primary ">등록</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/js/common.js"></script>
<script>
  $('.platform').submit(function(e) {
    e.preventDefault();
    let step = Number($(this).attr('data-step'));
    let pcode = null;
    let ppcode = null;
    let name = $('#platform').val();
    addCategory(name, pcode, ppcode, step);
  })

  $('.development').submit(function(e) {
    e.preventDefault();
    let step = Number($(this).attr('data-step'));
    console.log(step);
    let pcode = $(`#pcode${step}`).val();
    let ppcode = null;
    let name = $('#development').val();
    if (!pcode && !name) {
      alert('Platforms을 선택해주세요');
      location.reload();
    } else {
      addCategory(name, pcode, ppcode, step);
    }
  })

  $('.technologies').submit(function(e) {
    e.preventDefault();
    let step = Number($(this).attr('data-step'));
    let pcode = $(`#pcode${step}`).val();
    let ppcode = $('.plats').val();
    let name = $('#technologies').val();
    if (!pcode && !ppcode && !name) {
      alert('Platforms과 Development를 선택해주세요');
      location.reload();
    } else {
      addCategory(name, pcode, ppcode, step);
    }

  })

  //makeOption($('.plats'), 2, $('.devs'), '');



  function addCategory(name, pcode, ppcode, step) {
    let data = {
      name: name,
      pcode: pcode,
      ppcode: ppcode,
      step: step
    }
    console.log(data);
    $.ajax({
      data: data,
      type: 'POST',
      async: false,
      dataType: 'json',
      url: 'category_insert.php',
      success: function(r_data) {
        console.log(r_data);
        if (r_data.result == 1) {
          alert('등록완료');
          location.reload(); //새로고침
        } else {
          alert('등록 실패');
        }
      },
      error: function(err) {
        console.log(err);
      }
    })
  }

  $('#plat').on('change', '.plat', function() {
    let platValue = $(this).val();
    //location.href = `?plat=${platValue}`;
    makeOption($(this), 2, $('.dev'), '').then(() => {
      $('.dev').trigger('change'); // dev의 change 이벤트 실행
    })
  });

  $('#dev').on('change', '.dev', function() {
    //console.log('platValue received in dev change:', platValue);
    makeOption($(this), 3, $('.tech'), $('.plat').val());
  });


  $(document).on('change', '.plats', function() {
    makeOption($(this), 2, $('.devs'), '');
  });

  $('.edit').on('click', '.cate_edit', function() {
    const lcid = $(this).attr('data-id'); // 카테고리 ID
    const name = $(`#editName${lcid}`).val(); // 수정된 이름
    console.log(lcid, name);
    if (!name) {
      alert('카테고리 이름을 입력해주세요.');
      return;
    }

    let data = {
      lcid: lcid,
      name: name
    }
    $.ajax({
      url: 'category_modify.php',
      type: 'POST',
      data: data,
      dataType: 'json',
      success: function(response) {
        if (response.result === 1) {
          alert('수정이 완료되었습니다.');
          location.reload(); // 페이지 새로고침
        } else {
          alert('수정 실패: ' + response.error);
        }
      },
      error: function(error) {
        console.error(error);
        alert('수정 중 오류가 발생했습니다.');
      }
    });
  });

  $('.edit').on('click', '.cate_del', function() {

    if (confirm('정말 삭제하시겠습니까?')) {
      const lcid = $(this).attr('data-id'); // 카테고리 ID
      console.log(lcid);
      let data = {
        lcid: lcid
      }
      $.ajax({
        url: 'category_delete.php',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
          if (response.result === 1) {
            alert('삭제가 완료되었습니다.');
            location.reload(); // 페이지 새로고침
          } else {
            alert('삭제 실패: ' + response.error);
          }
        },
        error: function(error) {
          console.error(error);
          alert('삭제 중 오류가 발생했습니다.');
        }
      });
    }
  });
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>