<?php 
    headerAdmin($data); 
    getModal('modalRoles',$data);
?>  
    <div id="contentAjax"></div> 
    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fas fa-user-tag mr-3"></i><?= $data['page_title']; ?>
            <?php if($_SESSION['permisosMod']['w']){ ?>
             <button class="btn btn-primary ml-2" type="button" onclick="openModal();" ><i class="fas fa-plus-circle mr-2"></i>Nuevo</button>
             <?php } ?>
          </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/roles"><?= $data['page_title']; ?></a></li>
        </ul>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableRoles">
                  <thead>
                    <tr class="bg-danger text-center">
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Descripcion</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
<?php footerAdmin($data); ?>