<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package     ExpressionEngine
 * @author      ExpressionEngine Dev Team
 * @copyright   Copyright (c) 2003 - 2017, EllisLab, Inc.
 * @license     http://expressionengine.com/user_guide/license.html
 * @link        http://expressionengine.com
 * @since       Version 2.0
 * @filesource
 */

$plugin_info = array(
    'pi_name'           => 'FM Protected Files',
    'pi_version'        => '1.0.0',
    'pi_author'         => 'Foster Made App Team appteam@fostermade.co',
    'pi_author_url'     => 'http://fostermade.co',
    'pi_description'    => 'Prevents access to files to members not in member group',
    'pi_usage'          => ''
);

/**
 * FM Protected Files Plugin
 *
 * @package    ExpressionEngine
 * @subpackage Addons
 * @category   Plugin
 * @author     Foster Made App Team appteam@fostermade.co
 * @link       http://fostermade.co
 */
class Fm_protected_files
{
    public $return_data;

    /**
     * Protect the file
     */
    public function protect()
    {
        // get params
        $allowedMemberGroups = ee()->TMPL->fetch_param('allowed_member_groups');
        $filename = ee()->TMPL->fetch_param('filename');
        $directoryId = ee()->TMPL->fetch_param('directory_id');

        $memberGroupId = ee()->session->userdata('group_id');
        $memberId = ee()->session->userdata('member_id');

        // check if the current member group has access to the file
        if ($memberId == 0 || ($allowedMemberGroups !== false && !in_array($memberGroupId, explode('|', $allowedMemberGroups)))) {
            // show 404 page if the user does not have access
            ee()->TMPL->show_404();
        }

        $serverPath = $this->getServerPath($directoryId);

        // validate that file exists
        if (!$filename || !file_exists($serverPath . $filename)) {
            ee()->TMPL->show_404();
        }

        // set header by content type
        header('Content-Type: ' . get_mime_by_extension($filename));

        // output the file
        readfile($serverPath . $filename);
    }

    /**
     * Get server path for the given directory
     *
     * @param $directoryId
     *
     * @return mixed
     */
    protected function getServerPath($directoryId)
    {
        // find directory information
        ee()->load->model('file_upload_preferences_model');

        $directory = ee()->file_upload_preferences_model->get_file_upload_preferences(
            NULL,
            $directoryId,
            TRUE
        );

        return $directory['server_path'];
    }
}

/* End of file pi.fm_protected_files.php */
/* Location: /system/expressionengine/third_party/fm_protected_files/pi.fm_protected_files.php */