<div class="modal fade modalPermisos" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <!-Titulo del Modal-->
            <h5 class="modal-title h4" >Permisos Roles de Usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            //<?php
              //dep($data); 
             //?>
            <div class="col-md-12">
              <div class="tile">
                <form action="" id="formPermisos" name="formPermisos">
                  <!--IMPUT OCULTO ALMACENAMOS EL IDROL--> 
                 <input id="idrol" type="hidden" name="idrol" value="<?= $data['idrol']; ?>" required=""> 
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Modulo</th>
                          <th>Ver</th>
                          <th>Crear</th>
                          <th>Actualizar</th>
                          <th>Eliminar</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                            $no=1;
                            $modulos = $data['modulos'];
                            //RECORREMOS EL MODULO
                            for ($i=0; $i < count($modulos); $i++) { 
                                
                                //INGRESAMOS AL MODULO Y LOS PERMISOS DEL MODULO
                                $permisos = $modulos[$i]['permisos'];
                                //VERIFICAMOS LOS PERMISOS ASIGNADO AL MODULO
                                $rCheck = $permisos['r'] == 1 ? " checked " : "";
                                $wCheck = $permisos['w'] == 1 ? " checked " : "";
                                //CHECK LE ASIGNA AL CHECHBOX VALOR VERDADERO
                                $uCheck = $permisos['u'] == 1 ? " checked " : "";
                                $dCheck = $permisos['d'] == 1 ? " checked " : "";
                                  
                                $idmod = $modulos[$i]['idmodulo'];
                        ?>
                        <tr>
                          <td>
                                <?= $no; ?>
                                <input type="hidden" name="modulos[<?= $i; ?>][idmodulo]" value="<?= $idmod ?>" required >
                            </td>
                            <td>
                                <!--NOMBRE DEL MODULO-->
                                <?= $modulos[$i]['titulo']; ?>
                            </td>
                            <td><div class="toggle-flip">
                                  <label>
                                    <input type="checkbox" name="modulos[<?= $i; ?>][r]" <?= $rCheck ?> ><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                  </label>
                                </div>
                            </td>
                            <td><div class="toggle-flip">
                                  <label>
                                    <input type="checkbox" name="modulos[<?= $i; ?>][w]" <?= $wCheck ?>><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                  </label>
                                </div>
                            </td>
                            <td><div class="toggle-flip">
                                  <label>
                                    <input type="checkbox" name="modulos[<?= $i; ?>][u]" <?= $uCheck ?>><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                  </label>
                                </div>
                            </td>
                            <td><div class="toggle-flip">
                                  <label>
                                    <input type="checkbox" name="modulos[<?= $i; ?>][d]" <?= $dCheck ?>><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                  </label>
                                </div>
                            </td>
                          </tr>
                          <?php 
                                $no++;
                            }
                            ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="text-center">
                          <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="app-menu__icon fas fa-sign-out-alt" aria-hidden="true"></i> Salir</button>
                      </div>
                  </div>
                </form>  
            </div>
        </div>
    </div>
  </div>
</div>