    
                    <div class="row">
                        <div class="col-md-5"><span class="text">Payment Date</span></div>
                        <div class="col-md-7">
                            <select class="selectpicker" id="xday">
                                <?php 
                                    for($i=$days[0];$i<=$days[count($days)-1];$i++){
                                        echo '<option value="'.$i.'"';
                                        if($i==$selday) echo 'selected';
                                        echo '>';
                                        echo date('jS',  strtotime(date('Y-m-'.$i)));
                                        echo ' of the month';
                                        echo '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-5"><span class="text">Frequency</span></div>
                        <div class="col-md-7">
                            <select class="selectpicker" id="xfreq">
                                <?php 
                                        foreach ($freq as $key => $value) {
                                            echo '<option value="'.trim($key).'"';
                                            if(trim($key)==$selfreq) echo 'selected';
                                            echo '>'.$value.'</option>';
                                        }
                                        
                                ?>
                            </select>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-5"><span class="text">Start Date</span></div>
                        <div class="col-md-7">
                            <select class="selectpicker" id="xstartdate">
                                <?php 
                                    for($i=1;$i<count($y5inadvance);$i++){
                                        echo '<option value="'.$y5inadvance[$i]['value'].'"';
                                        if($y5inadvance[$i]['value']==$selstart) echo 'selected';
                                        echo '>';
                                        echo $y5inadvance[$i]['date'];
                                        echo '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-5"><span class="text">End Date</span></div>
                        <div class="col-md-7">
                            <select class="selectpicker" id="xenddate" <?php if($isdrp==1) echo 'disabled';?>>
                                <?php 
                                    
                                        for($i=0;$i<count($y5inadvance);$i++){
                                            echo '<option value="'.$y5inadvance[$i]['value'].'"';
                                            if($y5inadvance[$i]['value']==$selend) echo 'selected';
                                            echo '>';
                                            echo $y5inadvance[$i]['date'];
                                            echo '</option>';
                                        }
                                    
                                ?>
                        </select>
                        </div>
                    </div>
                    <br/>
                    