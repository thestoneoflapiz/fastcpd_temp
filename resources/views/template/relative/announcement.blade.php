<style>
.header {
  background-color: #f1f1f1;
  text-align: center;
  z-index: 99;
}

#navbar {
	z-index: 96;
  overflow: hidden;
  
}

.announcement-primary  {
  float: left;
  display: block;
  color: #CFFAF1;
  text-align: center;
  padding-top: 3px;
  text-decoration: none;
  font-size: 17px;
  background-color: #286090;
}

.announcement-success  {
  float: left;
  display: block;
  color: #CFFAF1;
  text-align: center;
  text-decoration: none;
  font-size: 17px;
  background-color: #0ABB87;
}

.announcement-secondary  {
  float: left;
  display: block;
  color: #CFFAF1;
  padding-top: 3px;
  text-align: center;
  text-decoration: none;
  font-size: 17px;
  background-color: #5A6268;
}
.announcement-danger  {
  float: left;
  display: block;
  color: #CFFAF1;
  padding-top: 3px;
  text-align: center;
  text-decoration: none;
  font-size: 17px;
  background-color: #C82333;
}

.announcement-warning  {
  float: left;
  display: block;
  color: #23272B;
  padding-top: 3px;
  text-align: center;
  text-decoration: none;
  font-size: 17px;
  background-color: #E0A800;
}

.announcement-info  {
  float: left;
  display: block;
  color: #CFFAF1;
  padding-top: 3px;
  text-align: center;
  text-decoration: none;
  font-size: 17px;
  background-color: #138496;
}
.announcement-light  {
  float: left;
  display: block;
  color: #CFFAF1;
  padding-top: 3px;
  text-align: center;
  text-decoration: none;
  font-size: 17px;
  background-color: #E2E6EA;
}
.announcement-dark  {
  float: left;
  display: block;
  color: #CFFAF1;
  padding-top: 3px;
  text-align: center;
  text-decoration: none;
  font-size: 17px;
  background-color: #23272B;
}

.white {
	color:#CFFAF1;
}

.black {
	color:#23272B;
}

#navbar a:hover {
  background-color: #ddd;
  color: black;
}

#navbar a.active {
  background-color: #4CAF50;
  color: white;
}

.content {
  padding: 16px;
}

.sticky {
  position: fixed;
  top: 0;
  width: 100%;
}

.sticky + .content {
  padding-top: 60px;
}
.right{text-align: right;}
.left{text-align: left;}
.center{text-align: center;}
.center_div{margin: auto;width: 90%;}
.margin-top50{margin-top:50px;}
.empty-space{padding:10px;}
</style>


<div id="navbar" class="sticky" style="display:none;">
	<table width = "100%" class="white" id="table_color">
		<tr>
			<td class="center">
				<div id="message"></div>
				<b><p id="time_remaining"></p></b>
			</td>
      <td class="center" onclick="closeAnnouncement()">
        <button type="button" class="close" id="close_announcement" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </td>
		</tr>
  </table>
  <input type="hidden" id="announcement_id">
	
</div>