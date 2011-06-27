<?php /*====================================================================================
		SamNews [http://samjlevy.com/samnews], open-source PHP social news application
    	sam j levy [http://samjlevy.com]

    	This program is free software: you can redistribute it and/or modify it under the
    	terms of the GNU General Public License as published by the Free Software
    	Foundation, either version 3 of the License, or (at your option) any later
    	version.

    	This program is distributed in the hope that it will be useful, but WITHOUT ANY
    	WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
    	PARTICULAR PURPOSE.  See the GNU General Public License for more details.

    	You should have received a copy of the GNU General Public License along with this
    	program.  If not, see <http://www.gnu.org/licenses/>.
      ====================================================================================*/
?>

    </td></tr>
	<tr class="bottom_row">
    	<td align="left" width="49%"><a href="http://samjlevy.com/samnews" target="_blank"><strong>SamNews</strong></a> v<?php echo VERSION; ?></td>
        <td align="left" width="2%" style="padding:0;" nowrap><?php if(MYSELF == "index") { echo "<a href='" . $feed_url . "' target='_blank'><img src='" . IMAGES_PATH . "/rss.png' align='left' /></a>"; if(isset($page_head)) echo "<span class='rss_text'><a href='" . $feed_url . "' target='_blank'>" . $page_head . "</a></span>"; } ?></td>
        <td align="right" width="49%"><a href="#top">top</a></td>
	</tr>
</table>
<a name="bottom"></a>

</body>
</html>