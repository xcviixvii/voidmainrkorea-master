<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>

<div class="az-content-body">
     <div class="az-content az-content-mail">
      <div class="container">
        <div class="content-wrapper w-100">
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <div class="d-sm-flex pb-4 mb-4 border-bottom">
                    <div class="d-flex align-items-center">
                      <h5 class="page-title mb-n2">Open Tickets</h5>
                      <p class="mt-2 mb-n1 ml-3 text-muted">230 Tickets</p>
                    </div>
                    <form class="ml-lg-auto d-flex pt-2 pt-md-0 align-items-stretch justify-content-end">
                      <input type="text" class="form-control w-50" placeholder="Search">
                      <button type="submit" class="btn btn-success no-wrap ml-4">Search Ticket</button>
                    </form>
                  </div>
                  <div class="nav-scroller">
                    <ul class="nav nav-tabs tickets-tab-switch" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link rounded active" id="open-tab" data-toggle="tab" href="#open-tickets" role="tab" aria-controls="open-tickets" aria-selected="true">Open Tickets <div class="badge">13</div></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link rounded" id="pending-tab" data-toggle="tab" href="#pending-tickets" role="tab" aria-controls="pending-tickets" aria-selected="false">Pending Tickets <div class="badge">50 </div></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link rounded" id="onhold-tab" data-toggle="tab" href="#onhold-tickets" role="tab" aria-controls="onhold-tickets" aria-selected="false">On-hold Tickets <div class="badge">29 </div>
                        </a>
                      </li>
                    </ul>
                  </div>


                  <div class="tab-content border-0 tab-content-basic">
                    <div class="tab-pane fade show active" id="open-tickets" role="tabpanel" aria-labelledby="open-tickets">

                      <div class="tickets-date-group"><i class="typcn icon typcn-calendar-outline"></i>
                      Tuesday, 21 May 2019 </div>

                      <a href="#" class="tickets-card row">
                        <div class="tickets-details col-lg-8 col-12">
                          <div class="wrapper">
                            <h5>#39033 - Design Admin Dashboard</h5>
                            <div class="badge badge-success">New</div>
                          </div>
                          <div class="wrapper text-muted d-none d-md-block">
                            <span>Assigned to</span>
                            <span>Brett Gonzales</span>
                            <span><i class="typcn icon typcn-time"></i>03:34AM</span>
                          </div>
                        </div>
                        <div class="ticket-float col-lg-2 col-sm-6 d-none d-md-block">
                          <span class="text-muted">Frank Briggs</span>
                        </div>
                        <div class="ticket-float col-lg-2 col-sm-6 d-none d-md-block">
                          <i class="category-icon typcn icon typcn-folder"></i>
                          <span class="text-muted">Wireframe</span>
                        </div>
                      </a>


                    </div>


                    <div class="tab-pane fade" id="pending-tickets" role="tabpanel" aria-labelledby="pending-tickets">
                      <div class="tickets-date-group"><i class="typcn icon typcn-calendar-outline"></i>Tuesday, 21 May 2019 </div>
                      <a href="#" class="tickets-card row">
                        <div class="tickets-details col-lg-8">
                          <div class="wrapper">
                            <h5>#39045 - Design Admin Dashboard</h5>
                          </div>
                          <div class="wrapper text-muted d-none d-md-block">
                            <span>Assigned to</span>
                            <span>Luella Sparks</span>
                            <span><i class="typcn icon typcn-time"></i>12:54PM</span>
                          </div>
                        </div>
                        <div class="ticket-float col-lg-2 col-sm-6">
                          <span class="text-muted">Hunter Garza</span>
                        </div>
                        <div class="ticket-float col-lg-2 col-sm-6">
                          <i class="category-icon typcn icon typcn-folder"></i>
                          <span class="text-muted">Concept</span>
                        </div>
                      </a>
                    </div>

                    <div class="tab-pane fade" id="onhold-tickets" role="tabpanel" aria-labelledby="onhold-tickets">
                      
                      <div class="tickets-date-group"><i class="typcn icon typcn-calendar-outline"></i>
                      Tuesday, 21 May 2019 
                      </div>
                      
                      <a href="#" class="tickets-card row">
                        <div class="tickets-details col-lg-8">
                          <div class="wrapper">
                            <h5>#29033 - Design Admin Dashboard</h5>
                            <div class="badge badge-success">New</div>
                          </div>
                          <div class="wrapper text-muted d-none d-md-block">
                            <span>Assigned to</span>
                            <span>Rhoda Jimenez</span>
                            <span><i class="typcn icon typcn-time"></i>01:27PM</span>
                          </div>
                        </div>
                        <div class="ticket-float col-lg-2 col-sm-6">
                          <span class="text-muted">Maria Cook</span>
                        </div>
                        <div class="ticket-float col-lg-2 col-sm-6">
                          <i class="category-icon typcn icon typcn-folder"></i>
                          <span class="text-muted">Deployed</span>
                        </div>
                      </a>

                      
                    
                    </div>
                  </div>
   
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- az-content -->

    
</div><!-- az-content-body --> 
