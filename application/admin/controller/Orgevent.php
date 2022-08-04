<?php

namespace app\admin\controller;

use app\common\controller\Backend;


/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Orgevent extends Backend
{

    /**
     * Orgevent模型对象
     * @var \app\admin\model\Orgevent
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Orgevent;
        $this->view->assign("statusList", $this->model->getStatusList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            //$this->error();
            $list = $this->model
                ->with(['admin'])
                ->where($where)
                ->where('admin_id',$this->auth->id)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {

                $row->getRelation('admin')->visible(['username']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 导出秩序册
     *
     * @param $ids
     * @return void
     */
    public function export($ids = null)
    {
        if (false === $this->request->isAjax()) {
            $this->error(__('Invalid parameters'));
        }
        $ids = $ids ?: $this->request->post('ids');
        if (empty($ids)) {
            $this->error(__('Parameter %s can not be empty', 'ids'));
        }


        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }

        $search = array(
            '{比赛名称}',
            '{比赛地点}',
            '{比赛时间}',
            '{指导单位}',
            '{主办单位}',
            '{承办单位}',
            '{协办单位}',
            '{赞助单位}',
            '{竞赛时间和地点}',
            '{竞赛项目}',
            '{参赛资格}',
            '{参赛办法}',
            '{竞赛办法}',
            '{纪律要求}',
            '{竞赛组委会}',
            '{录取名次和奖励办法}',
            '{报名和报到}',
            '{经费保障}'
        );
        $data = $this->model
        ->where('id',$ids)
        ->find();

        $replace = array(
            $data->name, 
            $data->address,
            $data->daterange,
            $data->zhidao,
            $data->zhuban,
            $data->chengban,
            $data->xieban,
            $data->zanzhu,
            $data->timeandaddress,
            $data->xiangmu,
            $data->zige,
            $data->cansaibanfa,
            $data->jingsaibanfa,
            $data->jilv,
            $data->zuweihui,
            $data->luquandjiangli,
            $data->baomingandbaodao,
            $data->jingfei
        );
        // var_dump($matchs);
        // $this->error('暂无数据','',$data);
        $file = str_replace($search, $replace, $this::$template);
        $this->success('即将开始下载', null, $file);
    }

    private static $template = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
    <?mso-application progid="Word.Document"?>
    <pkg:package
        xmlns:pkg="http://schemas.microsoft.com/office/2006/xmlPackage">
        <pkg:part pkg:name="/_rels/.rels" pkg:contentType="application/vnd.openxmlformats-package.relationships+xml" pkg:padding="512">
            <pkg:xmlData>
                <Relationships
                    xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
                    <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
                    <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
                    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
                </Relationships>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/document.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml">
            <pkg:xmlData>
                <w:document
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:body>
                        <w:p w14:paraId="2E684FAA" w14:textId="77777777" w:rsidR="00B333F2" w:rsidRDefault="00B333F2" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="distribute"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="40"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="083C940E" w14:textId="77777777" w:rsidR="00B333F2" w:rsidRDefault="00B333F2" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="distribute"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="40"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="5972E853" w14:textId="72C4F01E" w:rsidR="00506A83" w:rsidRDefault="001E01A9" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="distribute"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="40"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r w:rsidRPr="00D04C4D">
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="40"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                                <w:t>{比赛名称}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="4B2408BD" w14:textId="40702895" w:rsidR="00D04C4D" w:rsidRDefault="00D04C4D" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="distribute"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="40"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="29D1A184" w14:textId="6A2E0012" w:rsidR="00D04C4D" w:rsidRDefault="00D04C4D" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="distribute"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="40"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="01C59872" w14:textId="2886B1B1" w:rsidR="00D04C4D" w:rsidRDefault="00D04C4D" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="distribute"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="40"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="525BA51F" w14:textId="77777777" w:rsidR="00B333F2" w:rsidRDefault="00B333F2" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="distribute"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="40"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="71E4F81F" w14:textId="77777777" w:rsidR="00D04C4D" w:rsidRDefault="00D04C4D" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hAnsi="黑体"/>
                                    <w:sz w:val="96"/>
                                    <w:szCs w:val="144"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r w:rsidRPr="00D04C4D">
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="96"/>
                                    <w:szCs w:val="144"/>
                                </w:rPr>
                                <w:t>秩</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="0F294CE0" w14:textId="77777777" w:rsidR="00D04C4D" w:rsidRDefault="00D04C4D" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hAnsi="黑体"/>
                                    <w:sz w:val="96"/>
                                    <w:szCs w:val="144"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r w:rsidRPr="00D04C4D">
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="96"/>
                                    <w:szCs w:val="144"/>
                                </w:rPr>
                                <w:t>序</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="0E3AFA70" w14:textId="3E76F7A1" w:rsidR="00D04C4D" w:rsidRDefault="00D04C4D" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hAnsi="黑体"/>
                                    <w:sz w:val="96"/>
                                    <w:szCs w:val="144"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r w:rsidRPr="00D04C4D">
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="96"/>
                                    <w:szCs w:val="144"/>
                                </w:rPr>
                                <w:t>册</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="3FE0A780" w14:textId="20DA7D97" w:rsidR="00B333F2" w:rsidRDefault="00B333F2" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hAnsi="黑体"/>
                                    <w:sz w:val="96"/>
                                    <w:szCs w:val="144"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="111C20AF" w14:textId="77777777" w:rsidR="00B333F2" w:rsidRPr="00B333F2" w:rsidRDefault="00B333F2" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hAnsi="黑体"/>
                                    <w:sz w:val="56"/>
                                    <w:szCs w:val="72"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="146C1447" w14:textId="2F8A2B3D" w:rsidR="00D04C4D" w:rsidRPr="00B333F2" w:rsidRDefault="00B333F2" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="36"/>
                                    <w:szCs w:val="40"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r w:rsidR="00D04C4D" w:rsidRPr="00B333F2">
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="36"/>
                                    <w:szCs w:val="40"/>
                                </w:rPr>
                                <w:t>{比赛地点}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="17B01D83" w14:textId="3E20ECC7" w:rsidR="00A1791B" w:rsidRDefault="00B333F2" w:rsidP="00D04C4D">
                            <w:pPr>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="36"/>
                                    <w:szCs w:val="40"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r w:rsidR="00D04C4D" w:rsidRPr="00B333F2">
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="36"/>
                                    <w:szCs w:val="40"/>
                                </w:rPr>
                                <w:t>{比赛时间}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="102854E1" w14:textId="77777777" w:rsidR="00A1791B" w:rsidRDefault="00A1791B">
                            <w:pPr>
                                <w:widowControl/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="36"/>
                                    <w:szCs w:val="40"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="36"/>
                                    <w:szCs w:val="40"/>
                                </w:rPr>
                                <w:br w:type="page"/>
                            </w:r>
                        </w:p>
                        <w:sdt>
                            <w:sdtPr>
                                <w:rPr>
                                    <w:rFonts w:asciiTheme="minorHAnsi" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorHAnsi" w:cstheme="minorBidi"/>
                                    <w:color w:val="auto"/>
                                    <w:kern w:val="2"/>
                                    <w:sz w:val="21"/>
                                    <w:szCs w:val="22"/>
                                    <w:lang w:val="zh-CN"/>
                                </w:rPr>
                                <w:id w:val="1228573162"/>
                                <w:docPartObj>
                                    <w:docPartGallery w:val="Table of Contents"/>
                                    <w:docPartUnique/>
                                </w:docPartObj>
                            </w:sdtPr>
                            <w:sdtEndPr>
                                <w:rPr>
                                    <w:b/>
                                    <w:bCs/>
                                </w:rPr>
                            </w:sdtEndPr>
                            <w:sdtContent>
                                <w:p w14:paraId="64201D32" w14:textId="3D8A50BD" w:rsidR="0026451C" w:rsidRPr="00532994" w:rsidRDefault="0026451C" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="TOC"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体"/>
                                            <w:color w:val="auto"/>
                                            <w:sz w:val="44"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00532994">
                                        <w:rPr>
                                            <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体"/>
                                            <w:color w:val="auto"/>
                                            <w:sz w:val="44"/>
                                            <w:lang w:val="zh-CN"/>
                                        </w:rPr>
                                        <w:t>目</w:t>
                                    </w:r>
                                    <w:r w:rsidR="00532994">
                                        <w:rPr>
                                            <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hint="eastAsia"/>
                                            <w:color w:val="auto"/>
                                            <w:sz w:val="44"/>
                                            <w:lang w:val="zh-CN"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve"></w:t>
                                    </w:r>
                                    <w:r w:rsidR="00532994">
                                        <w:rPr>
                                            <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体"/>
                                            <w:color w:val="auto"/>
                                            <w:sz w:val="44"/>
                                            <w:lang w:val="zh-CN"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve"></w:t>
                                    </w:r>
                                    <w:r w:rsidRPr="00532994">
                                        <w:rPr>
                                            <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体"/>
                                            <w:color w:val="auto"/>
                                            <w:sz w:val="44"/>
                                            <w:lang w:val="zh-CN"/>
                                        </w:rPr>
                                        <w:t>录</w:t>
                                    </w:r>
                                </w:p>
                                <w:p w14:paraId="559D3D75" w14:textId="3373F59D" w:rsidR="00532994" w:rsidRDefault="0026451C" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:fldChar w:fldCharType="begin"/>
                                    </w:r>
                                    <w:r>
                                        <w:instrText xml:space="preserve"> TOC \o "1-3" \h \z \u </w:instrText>
                                    </w:r>
                                    <w:r>
                                        <w:fldChar w:fldCharType="separate"/>
                                    </w:r>
                                    <w:hyperlink w:anchor="_Toc110029695" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>一、 指导单位</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029695 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="0C4BA486" w14:textId="5FE793DC" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029696" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>二、 主办单位</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029696 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="4CF35530" w14:textId="391483AD" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029697" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>三、 承办单位</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029697 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="2E17E83B" w14:textId="6C30DD95" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029698" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>四、 协办单位</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029698 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="6634F872" w14:textId="1AD15B87" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029699" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>五、 赞助单位</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029699 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="192C2587" w14:textId="5D98C1E0" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029700" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>六、 竞赛时间和地点</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029700 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="4DA86609" w14:textId="21B93239" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029701" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>七、 竞赛项目</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029701 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="505F57BC" w14:textId="1E0F0531" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029702" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>八、 参赛资格</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029702 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="74B5B9C9" w14:textId="5AF56DFE" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029703" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>九、 参赛办法</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029703 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="7AA542D6" w14:textId="7BD0B1AF" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029704" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>十、 竞赛办法</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029704 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>1</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="3101FE75" w14:textId="23F80B93" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029705" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>十一、 纪律要求</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029705 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>2</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="348BABF1" w14:textId="35DF6984" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029706" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>十二、 竞赛组委会</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029706 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>2</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="6D50036E" w14:textId="622AA7CC" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029707" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>十三、 录取名次及奖励办法</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029707 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>2</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="31A08CB8" w14:textId="15847B3F" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029708" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>十四、 报名与报到</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029708 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>2</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="77434F20" w14:textId="34AD6452" w:rsidR="00532994" w:rsidRDefault="00000000" w:rsidP="00532994">
                                    <w:pPr>
                                        <w:pStyle w:val="a9"/>
                                    </w:pPr>
                                    <w:hyperlink w:anchor="_Toc110029709" w:history="1">
                                        <w:r w:rsidR="00532994" w:rsidRPr="00ED049A">
                                            <w:rPr>
                                                <w:rStyle w:val="a8"/>
                                            </w:rPr>
                                            <w:t>十五、 经费保障</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:tab/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="begin"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:instrText xml:space="preserve"> PAGEREF _Toc110029709 \h </w:instrText>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="separate"/>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:t>2</w:t>
                                        </w:r>
                                        <w:r w:rsidR="00532994">
                                            <w:rPr>
                                                <w:webHidden/>
                                            </w:rPr>
                                            <w:fldChar w:fldCharType="end"/>
                                        </w:r>
                                    </w:hyperlink>
                                </w:p>
                                <w:p w14:paraId="3F0CE3C7" w14:textId="6C3F681F" w:rsidR="0026451C" w:rsidRDefault="0026451C">
                                    <w:r>
                                        <w:rPr>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:lang w:val="zh-CN"/>
                                        </w:rPr>
                                        <w:fldChar w:fldCharType="end"/>
                                    </w:r>
                                </w:p>
                            </w:sdtContent>
                        </w:sdt>
                        <w:p w14:paraId="3F3CBDF6" w14:textId="77777777" w:rsidR="00C01D34" w:rsidRDefault="00C01D34" w:rsidP="006A7F94">
                            <w:pPr>
                                <w:spacing w:line="600" w:lineRule="exact"/>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体"/>
                                    <w:sz w:val="44"/>
                                </w:rPr>
                                <w:sectPr w:rsidR="00C01D34" w:rsidSect="006A7F94">
                                    <w:headerReference w:type="even" r:id="rId8"/>
                                    <w:headerReference w:type="default" r:id="rId9"/>
                                    <w:footerReference w:type="even" r:id="rId10"/>
                                    <w:footerReference w:type="default" r:id="rId11"/>
                                    <w:headerReference w:type="first" r:id="rId12"/>
                                    <w:footerReference w:type="first" r:id="rId13"/>
                                    <w:pgSz w:w="11906" w:h="16838"/>
                                    <w:pgMar w:top="1440" w:right="1800" w:bottom="1440" w:left="1800" w:header="851" w:footer="992" w:gutter="0"/>
                                    <w:cols w:space="425"/>
                                    <w:docGrid w:type="lines" w:linePitch="312"/>
                                </w:sectPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="4A4F1D32" w14:textId="446279B1" w:rsidR="0026451C" w:rsidRPr="006A7F94" w:rsidRDefault="006A7F94" w:rsidP="006A7F94">
                            <w:pPr>
                                <w:spacing w:line="600" w:lineRule="exact"/>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体"/>
                                    <w:sz w:val="44"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r w:rsidRPr="0077770C">
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hint="eastAsia"/>
                                    <w:sz w:val="44"/>
                                </w:rPr>
                                <w:lastRenderedPageBreak/>
                                <w:t>{比赛名称}</w:t>
                            </w:r>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体"/>
                                    <w:sz w:val="44"/>
                                </w:rPr>
                                <w:br/>
                            </w:r>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体" w:hint="eastAsia"/>
                                    <w:sz w:val="44"/>
                                </w:rPr>
                                <w:t>竞赛规程</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="11170B0B" w14:textId="3A8EBBC0" w:rsidR="00D772E1" w:rsidRDefault="00D772E1" w:rsidP="00C95671">
                            <w:pPr>
                                <w:spacing w:line="240" w:lineRule="exact"/>
                                <w:jc w:val="center"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="方正小标宋简体" w:eastAsia="方正小标宋简体"/>
                                    <w:sz w:val="44"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:p w14:paraId="10C5999B" w14:textId="664ABDCE" w:rsidR="00C95671" w:rsidRDefault="009B366C" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="0" w:name="_Toc110029695"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>指导单位</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="0"/>
                        </w:p>
                        <w:p w14:paraId="5C60753B" w14:textId="1A767D35" w:rsidR="00E933BC" w:rsidRPr="00C301CF" w:rsidRDefault="007846C2" w:rsidP="000418B1">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{指导单位}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="64119186" w14:textId="475681F6" w:rsidR="009B366C" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="1" w:name="_Toc110029696"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>主办单位</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="1"/>
                        </w:p>
                        <w:p w14:paraId="5CC4994D" w14:textId="63250ED1" w:rsidR="005018DF" w:rsidRPr="005018DF" w:rsidRDefault="005018DF" w:rsidP="005018DF">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r w:rsidRPr="005018DF">
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{主办单位}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="558BCC12" w14:textId="1FB65553" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="2" w:name="_Toc110029697"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>承办单位</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="2"/>
                        </w:p>
                        <w:p w14:paraId="30F40C1F" w14:textId="1A6E687B" w:rsidR="005018DF" w:rsidRPr="005018DF" w:rsidRDefault="005018DF" w:rsidP="005018DF">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{承办单位}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="31905266" w14:textId="18AAF6E6" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="3" w:name="_Toc110029698"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>协办单位</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="3"/>
                        </w:p>
                        <w:p w14:paraId="71CEA294" w14:textId="31FF1CC0" w:rsidR="005018DF" w:rsidRPr="00532A7E" w:rsidRDefault="007C1C35" w:rsidP="00532A7E">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:ind w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{协办单位}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="2238DC5F" w14:textId="6E65F7CB" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="4" w:name="_Toc110029699"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>赞助单位</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="4"/>
                        </w:p>
                        <w:p w14:paraId="58E02560" w14:textId="51B1C09D" w:rsidR="000C0CE5" w:rsidRPr="00B171A5" w:rsidRDefault="00025CB2" w:rsidP="00B171A5">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{赞助单位}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="30239C30" w14:textId="0BF438F8" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="5" w:name="_Toc110029700"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>竞赛时间和地点</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="5"/>
                        </w:p>
                        <w:p w14:paraId="333A4262" w14:textId="2005519E" w:rsidR="00B93B8B" w:rsidRPr="00084976" w:rsidRDefault="001F674D" w:rsidP="00084976">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{竞赛时间和地点}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="57D93CD7" w14:textId="6C960136" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="6" w:name="_Toc110029701"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>竞赛项目</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="6"/>
                        </w:p>
                        <w:p w14:paraId="51B95E44" w14:textId="28D39552" w:rsidR="00944B2B" w:rsidRPr="00D15246" w:rsidRDefault="00B32810" w:rsidP="00D15246">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{竞赛项目}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="0CAD4C4C" w14:textId="13DBA934" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="7" w:name="_Toc110029702"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>参赛资格</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="7"/>
                        </w:p>
                        <w:p w14:paraId="6CF6DBA5" w14:textId="6821C860" w:rsidR="001F0DC6" w:rsidRPr="00777706" w:rsidRDefault="00CC309E" w:rsidP="00777706">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{参赛资格}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="1D92C6E7" w14:textId="3D0AA602" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="8" w:name="_Toc110029703"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>参赛办法</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="8"/>
                        </w:p>
                        <w:p w14:paraId="5C8F63EE" w14:textId="208EE5E1" w:rsidR="00C05320" w:rsidRPr="00A41516" w:rsidRDefault="00955D72" w:rsidP="00A41516">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{参赛办法}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="18D67F6F" w14:textId="69D21D0E" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="9" w:name="_Toc110029704"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>竞赛办法</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="9"/>
                        </w:p>
                        <w:p w14:paraId="3CB189B6" w14:textId="7F0E451A" w:rsidR="00435526" w:rsidRPr="00AC638F" w:rsidRDefault="00AC638F" w:rsidP="00AC638F">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{竞赛办法}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="39C9F1EE" w14:textId="085EA145" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="10" w:name="_Toc110029705"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:lastRenderedPageBreak/>
                                <w:t>纪律要求</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="10"/>
                        </w:p>
                        <w:p w14:paraId="315D9328" w14:textId="060D37B8" w:rsidR="00C5197A" w:rsidRPr="006D18CC" w:rsidRDefault="006D18CC" w:rsidP="006D18CC">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:ind w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{纪律要求}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="0783FBAD" w14:textId="7D065021" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="11" w:name="_Toc110029706"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>竞赛组委会</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="11"/>
                        </w:p>
                        <w:p w14:paraId="763D0DF4" w14:textId="7522C9AC" w:rsidR="002B460F" w:rsidRPr="00A516F1" w:rsidRDefault="00A516F1" w:rsidP="00A516F1">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{竞赛组委会}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="68948C33" w14:textId="3A4C1D3F" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="12" w:name="_Toc110029707"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>录取名次及奖励办法</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="12"/>
                        </w:p>
                        <w:p w14:paraId="150AC471" w14:textId="74200C60" w:rsidR="00326E93" w:rsidRPr="00E0128D" w:rsidRDefault="00947778" w:rsidP="00E0128D">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{录取名次和奖励办法}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="55D27897" w14:textId="37480584" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="13" w:name="_Toc110029708"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>报名与报到</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="13"/>
                        </w:p>
                        <w:p w14:paraId="61085EEF" w14:textId="54F2E669" w:rsidR="005725D3" w:rsidRPr="005725D3" w:rsidRDefault="005725D3" w:rsidP="005725D3">
                            <w:pPr>
                                <w:ind w:firstLineChars="200" w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{报名和报到}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="612EC693" w14:textId="50051029" w:rsidR="00D37E8D" w:rsidRDefault="00D37E8D" w:rsidP="009B366C">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:numPr>
                                    <w:ilvl w:val="0"/>
                                    <w:numId w:val="2"/>
                                </w:numPr>
                                <w:ind w:firstLineChars="0"/>
                                <w:jc w:val="left"/>
                                <w:outlineLvl w:val="0"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:bookmarkStart w:id="14" w:name="_Toc110029709"/>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>经费保障</w:t>
                            </w:r>
                            <w:bookmarkEnd w:id="14"/>
                        </w:p>
                        <w:p w14:paraId="6D49D70C" w14:textId="1B1EBCAF" w:rsidR="00CF048D" w:rsidRDefault="00CF048D" w:rsidP="00CF048D">
                            <w:pPr>
                                <w:pStyle w:val="a5"/>
                                <w:ind w:firstLine="640"/>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                            <w:r>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋" w:hint="eastAsia"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                                <w:t>{经费保障}</w:t>
                            </w:r>
                        </w:p>
                        <w:p w14:paraId="0539523B" w14:textId="77777777" w:rsidR="00376F7B" w:rsidRPr="00376F7B" w:rsidRDefault="00376F7B" w:rsidP="00376F7B">
                            <w:pPr>
                                <w:jc w:val="left"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="仿宋" w:eastAsia="仿宋" w:hAnsi="仿宋"/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:pPr>
                        </w:p>
                        <w:sectPr w:rsidR="00376F7B" w:rsidRPr="00376F7B" w:rsidSect="00C01D34">
                            <w:footerReference w:type="default" r:id="rId14"/>
                            <w:pgSz w:w="11906" w:h="16838"/>
                            <w:pgMar w:top="1440" w:right="1800" w:bottom="1440" w:left="1800" w:header="851" w:footer="992" w:gutter="0"/>
                            <w:pgNumType w:start="1"/>
                            <w:cols w:space="425"/>
                            <w:docGrid w:type="lines" w:linePitch="312"/>
                        </w:sectPr>
                    </w:body>
                </w:document>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/_rels/document.xml.rels" pkg:contentType="application/vnd.openxmlformats-package.relationships+xml" pkg:padding="256">
            <pkg:xmlData>
                <Relationships
                    xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
                    <Relationship Id="rId8" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/header" Target="header1.xml"/>
                    <Relationship Id="rId13" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer" Target="footer3.xml"/>
                    <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
                    <Relationship Id="rId7" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/endnotes" Target="endnotes.xml"/>
                    <Relationship Id="rId12" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/header" Target="header3.xml"/>
                    <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/numbering" Target="numbering.xml"/>
                    <Relationship Id="rId16" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="theme/theme1.xml"/>
                    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/customXml" Target="../customXml/item1.xml"/>
                    <Relationship Id="rId6" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footnotes" Target="footnotes.xml"/>
                    <Relationship Id="rId11" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer" Target="footer2.xml"/>
                    <Relationship Id="rId5" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/webSettings" Target="webSettings.xml"/>
                    <Relationship Id="rId15" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/fontTable" Target="fontTable.xml"/>
                    <Relationship Id="rId10" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer" Target="footer1.xml"/>
                    <Relationship Id="rId4" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings" Target="settings.xml"/>
                    <Relationship Id="rId9" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/header" Target="header2.xml"/>
                    <Relationship Id="rId14" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer" Target="footer4.xml"/>
                </Relationships>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/footnotes.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footnotes+xml">
            <pkg:xmlData>
                <w:footnotes
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:footnote w:type="separator" w:id="-1">
                        <w:p w14:paraId="08B20BDA" w14:textId="77777777" w:rsidR="00334B14" w:rsidRDefault="00334B14" w:rsidP="006A7F94">
                            <w:r>
                                <w:separator/>
                            </w:r>
                        </w:p>
                    </w:footnote>
                    <w:footnote w:type="continuationSeparator" w:id="0">
                        <w:p w14:paraId="2F62F979" w14:textId="77777777" w:rsidR="00334B14" w:rsidRDefault="00334B14" w:rsidP="006A7F94">
                            <w:r>
                                <w:continuationSeparator/>
                            </w:r>
                        </w:p>
                    </w:footnote>
                </w:footnotes>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/endnotes.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.endnotes+xml">
            <pkg:xmlData>
                <w:endnotes
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:endnote w:type="separator" w:id="-1">
                        <w:p w14:paraId="178789D8" w14:textId="77777777" w:rsidR="00334B14" w:rsidRDefault="00334B14" w:rsidP="006A7F94">
                            <w:r>
                                <w:separator/>
                            </w:r>
                        </w:p>
                    </w:endnote>
                    <w:endnote w:type="continuationSeparator" w:id="0">
                        <w:p w14:paraId="6B04F1D4" w14:textId="77777777" w:rsidR="00334B14" w:rsidRDefault="00334B14" w:rsidP="006A7F94">
                            <w:r>
                                <w:continuationSeparator/>
                            </w:r>
                        </w:p>
                    </w:endnote>
                </w:endnotes>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/header1.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.header+xml">
            <pkg:xmlData>
                <w:hdr
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:p w14:paraId="013EF82C" w14:textId="77777777" w:rsidR="006A7F94" w:rsidRDefault="006A7F94">
                        <w:pPr>
                            <w:pStyle w:val="a3"/>
                        </w:pPr>
                    </w:p>
                </w:hdr>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/header2.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.header+xml">
            <pkg:xmlData>
                <w:hdr
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:p w14:paraId="4B72B292" w14:textId="77777777" w:rsidR="006A7F94" w:rsidRDefault="006A7F94">
                        <w:pPr>
                            <w:pStyle w:val="a3"/>
                        </w:pPr>
                    </w:p>
                </w:hdr>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/footer1.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml">
            <pkg:xmlData>
                <w:ftr
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:p w14:paraId="555EAEBD" w14:textId="77777777" w:rsidR="006A7F94" w:rsidRDefault="006A7F94" w:rsidP="006A7F94">
                        <w:pPr>
                            <w:pStyle w:val="a6"/>
                            <w:jc w:val="right"/>
                        </w:pPr>
                    </w:p>
                </w:ftr>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/footer2.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml">
            <pkg:xmlData>
                <w:ftr
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:p w14:paraId="579D00D3" w14:textId="6DF8637D" w:rsidR="006A7F94" w:rsidRPr="006A7F94" w:rsidRDefault="006A7F94" w:rsidP="006A7F94">
                        <w:pPr>
                            <w:pStyle w:val="a6"/>
                            <w:jc w:val="right"/>
                            <w:rPr>
                                <w:rFonts w:ascii="宋体" w:eastAsia="宋体" w:hAnsi="宋体"/>
                                <w:sz w:val="28"/>
                            </w:rPr>
                        </w:pPr>
                    </w:p>
                </w:ftr>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/header3.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.header+xml">
            <pkg:xmlData>
                <w:hdr
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:p w14:paraId="46FA8FD8" w14:textId="77777777" w:rsidR="006A7F94" w:rsidRDefault="006A7F94">
                        <w:pPr>
                            <w:pStyle w:val="a3"/>
                        </w:pPr>
                    </w:p>
                </w:hdr>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/footer3.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml">
            <pkg:xmlData>
                <w:ftr
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:p w14:paraId="16125F2B" w14:textId="77777777" w:rsidR="006A7F94" w:rsidRDefault="006A7F94" w:rsidP="006A7F94">
                        <w:pPr>
                            <w:pStyle w:val="a6"/>
                            <w:jc w:val="right"/>
                        </w:pPr>
                    </w:p>
                </w:ftr>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/footer4.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml">
            <pkg:xmlData>
                <w:ftr
                    xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                    xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                    xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                    xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                    xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                    xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                    xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                    xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                    xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                    xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                    xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                    xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                    xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                    xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                    xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                    xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                    xmlns:w10="urn:schemas-microsoft-com:office:word"
                    xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                    xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                    xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                    xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                    xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                    xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                    xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                    xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                    xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                    xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                    xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                    xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                    <w:p w14:paraId="49F65A54" w14:textId="77777777" w:rsidR="00C01D34" w:rsidRPr="006A7F94" w:rsidRDefault="00C01D34" w:rsidP="006A7F94">
                        <w:pPr>
                            <w:pStyle w:val="a6"/>
                            <w:jc w:val="right"/>
                            <w:rPr>
                                <w:rFonts w:ascii="宋体" w:eastAsia="宋体" w:hAnsi="宋体"/>
                                <w:sz w:val="28"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="宋体" w:eastAsia="宋体" w:hAnsi="宋体"/>
                                <w:sz w:val="28"/>
                            </w:rPr>
                            <w:t xml:space="preserve">— </w:t>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="宋体" w:eastAsia="宋体" w:hAnsi="宋体"/>
                                <w:sz w:val="28"/>
                            </w:rPr>
                            <w:fldChar w:fldCharType="begin"/>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="宋体" w:eastAsia="宋体" w:hAnsi="宋体"/>
                                <w:sz w:val="28"/>
                            </w:rPr>
                            <w:instrText xml:space="preserve"> PAGE \* Arabic \* MERGEFORMAT </w:instrText>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="宋体" w:eastAsia="宋体" w:hAnsi="宋体"/>
                                <w:sz w:val="28"/>
                            </w:rPr>
                            <w:fldChar w:fldCharType="separate"/>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="宋体" w:eastAsia="宋体" w:hAnsi="宋体"/>
                                <w:noProof/>
                                <w:sz w:val="28"/>
                            </w:rPr>
                            <w:t>4</w:t>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="宋体" w:eastAsia="宋体" w:hAnsi="宋体"/>
                                <w:sz w:val="28"/>
                            </w:rPr>
                            <w:fldChar w:fldCharType="end"/>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="宋体" w:eastAsia="宋体" w:hAnsi="宋体"/>
                                <w:sz w:val="28"/>
                            </w:rPr>
                            <w:t xml:space="preserve"> —</w:t>
                        </w:r>
                    </w:p>
                </w:ftr>
            </pkg:xmlData>
        </pkg:part>
        <pkg:part pkg:name="/word/theme/theme1.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.theme+xml">
            <pkg:xmlData>
                <a:theme
                    xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" name="Office 主题​​">
                    <a:themeElements>
                        <a:clrScheme name="Office">
                            <a:dk1>
                                <a:sysClr val="windowText" lastClr="000000"/>
                            </a:dk1>
                            <a:lt1>
                                <a:sysClr val="window" lastClr="FFFFFF"/>
                            </a:lt1>
                            <a:dk2>
                                <a:srgbClr val="44546A"/>
                            </a:dk2>
                            <a:lt2>
                                <a:srgbClr val="E7E6E6"/>
                            </a:lt2>
                            <a:accent1>
                                <a:srgbClr val="4472C4"/>
                            </a:accent1>
                            <a:accent2>
                                <a:srgbClr val="ED7D31"/>
                            </a:accent2>
                            <a:accent3>
                                <a:srgbClr val="A5A5A5"/>
                            </a:accent3>
                            <a:accent4>
                                <a:srgbClr val="FFC000"/>
                            </a:accent4>
                            <a:accent5>
                                <a:srgbClr val="5B9BD5"/>
                            </a:accent5>
                            <a:accent6>
                                <a:srgbClr val="70AD47"/>
                            </a:accent6>
                            <a:hlink>
                                <a:srgbClr val="0563C1"/>
                            </a:hlink>
                            <a:folHlink>
                                <a:srgbClr val="954F72"/>
                            </a:folHlink>
                        </a:clrScheme>
                        <a:fontScheme name="Office">
                            <a:majorFont>
                                <a:latin typeface="等线 Light" panose="020F0302020204030204"/>
                                <a:ea typeface=""/>
                                <a:cs typeface=""/>
                                <a:font script="Jpan" typeface="游ゴシック Light"/>
                                <a:font script="Hang" typeface="맑은 고딕"/>
                                <a:font script="Hans" typeface="等线 Light"/>
                                <a:font script="Hant" typeface="新細明體"/>
                                <a:font script="Arab" typeface="Times New Roman"/>
                                <a:font script="Hebr" typeface="Times New Roman"/>
                                <a:font script="Thai" typeface="Angsana New"/>
                                <a:font script="Ethi" typeface="Nyala"/>
                                <a:font script="Beng" typeface="Vrinda"/>
                                <a:font script="Gujr" typeface="Shruti"/>
                                <a:font script="Khmr" typeface="MoolBoran"/>
                                <a:font script="Knda" typeface="Tunga"/>
                                <a:font script="Guru" typeface="Raavi"/>
                                <a:font script="Cans" typeface="Euphemia"/>
                                <a:font script="Cher" typeface="Plantagenet Cherokee"/>
                                <a:font script="Yiii" typeface="Microsoft Yi Baiti"/>
                                <a:font script="Tibt" typeface="Microsoft Himalaya"/>
                                <a:font script="Thaa" typeface="MV Boli"/>
                                <a:font script="Deva" typeface="Mangal"/>
                                <a:font script="Telu" typeface="Gautami"/>
                                <a:font script="Taml" typeface="Latha"/>
                                <a:font script="Syrc" typeface="Estrangelo Edessa"/>
                                <a:font script="Orya" typeface="Kalinga"/>
                                <a:font script="Mlym" typeface="Kartika"/>
                                <a:font script="Laoo" typeface="DokChampa"/>
                                <a:font script="Sinh" typeface="Iskoola Pota"/>
                                <a:font script="Mong" typeface="Mongolian Baiti"/>
                                <a:font script="Viet" typeface="Times New Roman"/>
                                <a:font script="Uigh" typeface="Microsoft Uighur"/>
                                <a:font script="Geor" typeface="Sylfaen"/>
                                <a:font script="Armn" typeface="Arial"/>
                                <a:font script="Bugi" typeface="Leelawadee UI"/>
                                <a:font script="Bopo" typeface="Microsoft JhengHei"/>
                                <a:font script="Java" typeface="Javanese Text"/>
                                <a:font script="Lisu" typeface="Segoe UI"/>
                                <a:font script="Mymr" typeface="Myanmar Text"/>
                                <a:font script="Nkoo" typeface="Ebrima"/>
                                <a:font script="Olck" typeface="Nirmala UI"/>
                                <a:font script="Osma" typeface="Ebrima"/>
                                <a:font script="Phag" typeface="Phagspa"/>
                                <a:font script="Syrn" typeface="Estrangelo Edessa"/>
                                <a:font script="Syrj" typeface="Estrangelo Edessa"/>
                                <a:font script="Syre" typeface="Estrangelo Edessa"/>
                                <a:font script="Sora" typeface="Nirmala UI"/>
                                <a:font script="Tale" typeface="Microsoft Tai Le"/>
                                <a:font script="Talu" typeface="Microsoft New Tai Lue"/>
                                <a:font script="Tfng" typeface="Ebrima"/>
                            </a:majorFont>
                            <a:minorFont>
                                <a:latin typeface="等线" panose="020F0502020204030204"/>
                                <a:ea typeface=""/>
                                <a:cs typeface=""/>
                                <a:font script="Jpan" typeface="游明朝"/>
                                <a:font script="Hang" typeface="맑은 고딕"/>
                                <a:font script="Hans" typeface="等线"/>
                                <a:font script="Hant" typeface="新細明體"/>
                                <a:font script="Arab" typeface="Arial"/>
                                <a:font script="Hebr" typeface="Arial"/>
                                <a:font script="Thai" typeface="Cordia New"/>
                                <a:font script="Ethi" typeface="Nyala"/>
                                <a:font script="Beng" typeface="Vrinda"/>
                                <a:font script="Gujr" typeface="Shruti"/>
                                <a:font script="Khmr" typeface="DaunPenh"/>
                                <a:font script="Knda" typeface="Tunga"/>
                                <a:font script="Guru" typeface="Raavi"/>
                                <a:font script="Cans" typeface="Euphemia"/>
                                <a:font script="Cher" typeface="Plantagenet Cherokee"/>
                                <a:font script="Yiii" typeface="Microsoft Yi Baiti"/>
                                <a:font script="Tibt" typeface="Microsoft Himalaya"/>
                                <a:font script="Thaa" typeface="MV Boli"/>
                                <a:font script="Deva" typeface="Mangal"/>
                                <a:font script="Telu" typeface="Gautami"/>
                                <a:font script="Taml" typeface="Latha"/>
                                <a:font script="Syrc" typeface="Estrangelo Edessa"/>
                                <a:font script="Orya" typeface="Kalinga"/>
                                <a:font script="Mlym" typeface="Kartika"/>
                                <a:font script="Laoo" typeface="DokChampa"/>
                                <a:font script="Sinh" typeface="Iskoola Pota"/>
                                <a:font script="Mong" typeface="Mongolian Baiti"/>
                                <a:font script="Viet" typeface="Arial"/>
                                <a:font script="Uigh" typeface="Microsoft Uighur"/>
                                <a:font script="Geor" typeface="Sylfaen"/>
                                <a:font script="Armn" typeface="Arial"/>
                                <a:font script="Bugi" typeface="Leelawadee UI"/>
                                <a:font script="Bopo" typeface="Microsoft JhengHei"/>
                                <a:font script="Java" typeface="Javanese Text"/>
                                <a:font script="Lisu" typeface="Segoe UI"/>
                                <a:font script="Mymr" typeface="Myanmar Text"/>
                                <a:font script="Nkoo" typeface="Ebrima"/>
                                <a:font script="Olck" typeface="Nirmala UI"/>
                                <a:font script="Osma" typeface="Ebrima"/>
                                <a:font script="Phag" typeface="Phagspa"/>
                                <a:font script="Syrn" typeface="Estrangelo Edessa"/>
                                <a:font script="Syrj" typeface="Estrangelo Edessa"/>
                                <a:font script="Syre" typeface="Estrangelo Edessa"/>
                                <a:font script="Sora" typeface="Nirmala UI"/>
                                <a:font script="Tale" typeface="Microsoft Tai Le"/>
                                <a:font script="Talu" typeface="Microsoft New Tai Lue"/>
                                <a:font script="Tfng" typeface="Ebrima"/>
                            </a:minorFont>
                        </a:fontScheme>
                        <a:fmtScheme name="Office">
                            <a:fillStyleLst>
                                <a:solidFill>
                                    <a:schemeClr val="phClr"/>
                                </a:solidFill>
                                <a:gradFill rotWithShape="1">
                                    <a:gsLst>
                                        <a:gs pos="0">
                                            <a:schemeClr val="phClr">
                                                <a:lumMod val="110000"/>
                                                <a:satMod val="105000"/>
                                                <a:tint val="67000"/>
                                            </a:schemeClr>
                                        </a:gs>
                                        <a:gs pos="50000">
                                            <a:schemeClr val="phClr">
                                                <a:lumMod val="105000"/>
                                                <a:satMod val="103000"/>
                                                <a:tint val="73000"/>
                                            </a:schemeClr>
                                        </a:gs>
                                        <a:gs pos="100000">
                                            <a:schemeClr val="phClr">
                                                <a:lumMod val="105000"/>
                                                <a:satMod val="109000"/>
                                                <a:tint val="81000"/>
                                            </a:schemeClr>
                                        </a:gs>
                                    </a:gsLst>
                                    <a:lin ang="5400000" scaled="0"/>
                                </a:gradFill>
                                <a:gradFill rotWithShape="1">
                                    <a:gsLst>
                                        <a:gs pos="0">
                                            <a:schemeClr val="phClr">
                                                <a:satMod val="103000"/>
                                                <a:lumMod val="102000"/>
                                                <a:tint val="94000"/>
                                            </a:schemeClr>
                                        </a:gs>
                                        <a:gs pos="50000">
                                            <a:schemeClr val="phClr">
                                                <a:satMod val="110000"/>
                                                <a:lumMod val="100000"/>
                                                <a:shade val="100000"/>
                                            </a:schemeClr>
                                        </a:gs>
                                        <a:gs pos="100000">
                                            <a:schemeClr val="phClr">
                                                <a:lumMod val="99000"/>
                                                <a:satMod val="120000"/>
                                                <a:shade val="78000"/>
                                            </a:schemeClr>
                                        </a:gs>
                                    </a:gsLst>
                                    <a:lin ang="5400000" scaled="0"/>
                                </a:gradFill>
                            </a:fillStyleLst>
                            <a:lnStyleLst>
                                <a:ln w="6350" cap="flat" cmpd="sng" algn="ctr">
                                    <a:solidFill>
                                        <a:schemeClr val="phClr"/>
                                    </a:solidFill>
                                    <a:prstDash val="solid"/>
                                    <a:miter lim="800000"/>
                                </a:ln>
                                <a:ln w="12700" cap="flat" cmpd="sng" algn="ctr">
                                    <a:solidFill>
                                        <a:schemeClr val="phClr"/>
                                    </a:solidFill>
                                    <a:prstDash val="solid"/>
                                    <a:miter lim="800000"/>
                                </a:ln>
                                <a:ln w="19050" cap="flat" cmpd="sng" algn="ctr">
                                    <a:solidFill>
                                        <a:schemeClr val="phClr"/>
                                    </a:solidFill>
                                    <a:prstDash val="solid"/>
                                    <a:miter lim="800000"/>
                                </a:ln>
                            </a:lnStyleLst>
                            <a:effectStyleLst>
                                <a:effectStyle>
                                    <a:effectLst/>
                                </a:effectStyle>
                                <a:effectStyle>
                                    <a:effectLst/>
                                </a:effectStyle>
                                <a:effectStyle>
                                    <a:effectLst>
                                        <a:outerShdw blurRad="57150" dist="19050" dir="5400000" algn="ctr" rotWithShape="0">
                                            <a:srgbClr val="000000">
                                                <a:alpha val="63000"/>
                                            </a:srgbClr>
                                        </a:outerShdw>
                                    </a:effectLst>
                                </a:effectStyle>
                            </a:effectStyleLst>
                            <a:bgFillStyleLst>
                                <a:solidFill>
                                    <a:schemeClr val="phClr"/>
                                </a:solidFill>
                                <a:solidFill>
                                    <a:schemeClr val="phClr">
                                        <a:tint val="95000"/>
                                        <a:satMod val="170000"/>
                                    </a:schemeClr>
                                </a:solidFill>
                                <a:gradFill rotWithShape="1">
                                    <a:gsLst>
                                        <a:gs pos="0">
                                            <a:schemeClr val="phClr">
                                                <a:tint val="93000"/>
                                                <a:satMod val="150000"/>
                                                <a:shade val="98000"/>
                                                <a:lumMod val="102000"/>
                                            </a:schemeClr>
                                        </a:gs>
                                        <a:gs pos="50000">
                                            <a:schemeClr val="phClr">
                                                <a:tint val="98000"/>
                                                <a:satMod val="130000"/>
                                                <a:shade val="90000"/>
                                                <a:lumMod val="103000"/>
                                            </a:schemeClr>
                                        </a:gs>
                                        <a:gs pos="100000">
                                            <a:schemeClr val="phClr">
                                                <a:shade val="63000"/>
                                                <a:satMod val="120000"/>
                                            </a:schemeClr>
                                        </a:gs>
                                    </a:gsLst>
                                    <a:lin ang="5400000" scaled="0"/>
                                </a:gradFill>
                            </a:bgFillStyleLst>
                        </a:fmtScheme>
                    </a:themeElements>
                    <a:objectDefaults/>
                    <a:extraClrSchemeLst/>
                    <a:extLst>
                        <a:ext uri="{05A4C25C-085E-4340-85A3-A5531E510DB2}">
                            <thm15:themeFamily
                                xmlns:thm15="http://schemas.microsoft.com/office/thememl/2012/main" name="Office Theme" id="{62F939B6-93AF-4DB8-9C6B-D6C7DFDC589F}" vid="{4A3C46E8-61CC-4603-A589-7422A47A8E4A}"/>
                            </a:ext>
                        </a:extLst>
                    </a:theme>
                </pkg:xmlData>
            </pkg:part>
            <pkg:part pkg:name="/word/settings.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.settings+xml">
                <pkg:xmlData>
                    <w:settings
                        xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                        xmlns:o="urn:schemas-microsoft-com:office:office"
                        xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                        xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                        xmlns:v="urn:schemas-microsoft-com:vml"
                        xmlns:w10="urn:schemas-microsoft-com:office:word"
                        xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                        xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                        xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                        xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                        xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                        xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                        xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                        xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                        xmlns:sl="http://schemas.openxmlformats.org/schemaLibrary/2006/main" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh">
                        <w:zoom w:percent="90"/>
                        <w:displayBackgroundShape/>
                        <w:bordersDoNotSurroundHeader/>
                        <w:bordersDoNotSurroundFooter/>
                        <w:defaultTabStop w:val="420"/>
                        <w:drawingGridHorizontalSpacing w:val="105"/>
                        <w:drawingGridVerticalSpacing w:val="156"/>
                        <w:displayHorizontalDrawingGridEvery w:val="2"/>
                        <w:displayVerticalDrawingGridEvery w:val="2"/>
                        <w:characterSpacingControl w:val="doNotCompress"/>
                        <w:footnotePr>
                            <w:footnote w:id="-1"/>
                            <w:footnote w:id="0"/>
                        </w:footnotePr>
                        <w:endnotePr>
                            <w:endnote w:id="-1"/>
                            <w:endnote w:id="0"/>
                        </w:endnotePr>
                        <w:compat>
                            <w:useFELayout/>
                            <w:compatSetting w:name="compatibilityMode" w:uri="http://schemas.microsoft.com/office/word" w:val="12"/>
                            <w:compatSetting w:name="useWord2013TrackBottomHyphenation" w:uri="http://schemas.microsoft.com/office/word" w:val="1"/>
                        </w:compat>
                        <w:rsids>
                            <w:rsidRoot w:val="00475E61"/>
                            <w:rsid w:val="00000FD0"/>
                            <w:rsid w:val="00002838"/>
                            <w:rsid w:val="00006274"/>
                            <w:rsid w:val="00006AE7"/>
                            <w:rsid w:val="00007870"/>
                            <w:rsid w:val="00010C67"/>
                            <w:rsid w:val="0001169A"/>
                            <w:rsid w:val="00012372"/>
                            <w:rsid w:val="0001332B"/>
                            <w:rsid w:val="00014BB5"/>
                            <w:rsid w:val="00016C6B"/>
                            <w:rsid w:val="000175D0"/>
                            <w:rsid w:val="00017FC4"/>
                            <w:rsid w:val="000205DE"/>
                            <w:rsid w:val="0002291C"/>
                            <w:rsid w:val="00024FEC"/>
                            <w:rsid w:val="0002578D"/>
                            <w:rsid w:val="00025CB2"/>
                            <w:rsid w:val="00025E1E"/>
                            <w:rsid w:val="00026A46"/>
                            <w:rsid w:val="0003162B"/>
                            <w:rsid w:val="000357B9"/>
                            <w:rsid w:val="00036C2D"/>
                            <w:rsid w:val="00037073"/>
                            <w:rsid w:val="000372A0"/>
                            <w:rsid w:val="000418B1"/>
                            <w:rsid w:val="00042246"/>
                            <w:rsid w:val="00045849"/>
                            <w:rsid w:val="000470EC"/>
                            <w:rsid w:val="00047CD4"/>
                            <w:rsid w:val="00051426"/>
                            <w:rsid w:val="0005258A"/>
                            <w:rsid w:val="00052E19"/>
                            <w:rsid w:val="00054F8C"/>
                            <w:rsid w:val="00055021"/>
                            <w:rsid w:val="000556EA"/>
                            <w:rsid w:val="00057A0B"/>
                            <w:rsid w:val="00060A54"/>
                            <w:rsid w:val="00064601"/>
                            <w:rsid w:val="00064B34"/>
                            <w:rsid w:val="000678FC"/>
                            <w:rsid w:val="00070E6A"/>
                            <w:rsid w:val="0007113F"/>
                            <w:rsid w:val="00073C8F"/>
                            <w:rsid w:val="00074941"/>
                            <w:rsid w:val="00074CAF"/>
                            <w:rsid w:val="00075CC6"/>
                            <w:rsid w:val="00075E79"/>
                            <w:rsid w:val="00080809"/>
                            <w:rsid w:val="00082116"/>
                            <w:rsid w:val="000831CC"/>
                            <w:rsid w:val="00084976"/>
                            <w:rsid w:val="00086300"/>
                            <w:rsid w:val="00086F04"/>
                            <w:rsid w:val="00090B5A"/>
                            <w:rsid w:val="0009237F"/>
                            <w:rsid w:val="0009335E"/>
                            <w:rsid w:val="000935A4"/>
                            <w:rsid w:val="00094979"/>
                            <w:rsid w:val="00095227"/>
                            <w:rsid w:val="00097484"/>
                            <w:rsid w:val="00097667"/>
                            <w:rsid w:val="00097AE2"/>
                            <w:rsid w:val="000A0815"/>
                            <w:rsid w:val="000A208A"/>
                            <w:rsid w:val="000A3964"/>
                            <w:rsid w:val="000A3F36"/>
                            <w:rsid w:val="000A5646"/>
                            <w:rsid w:val="000B072F"/>
                            <w:rsid w:val="000B0FCF"/>
                            <w:rsid w:val="000B158A"/>
                            <w:rsid w:val="000B1B02"/>
                            <w:rsid w:val="000B2276"/>
                            <w:rsid w:val="000B39B9"/>
                            <w:rsid w:val="000B52C7"/>
                            <w:rsid w:val="000B5C69"/>
                            <w:rsid w:val="000C0CE5"/>
                            <w:rsid w:val="000C3A06"/>
                            <w:rsid w:val="000C5884"/>
                            <w:rsid w:val="000C5932"/>
                            <w:rsid w:val="000C6911"/>
                            <w:rsid w:val="000C7647"/>
                            <w:rsid w:val="000D1638"/>
                            <w:rsid w:val="000D1740"/>
                            <w:rsid w:val="000D418A"/>
                            <w:rsid w:val="000D41F0"/>
                            <w:rsid w:val="000D4EF8"/>
                            <w:rsid w:val="000D576C"/>
                            <w:rsid w:val="000D6865"/>
                            <w:rsid w:val="000E0318"/>
                            <w:rsid w:val="000E094E"/>
                            <w:rsid w:val="000E1BDF"/>
                            <w:rsid w:val="000E4589"/>
                            <w:rsid w:val="000E5759"/>
                            <w:rsid w:val="000E697A"/>
                            <w:rsid w:val="000F0860"/>
                            <w:rsid w:val="000F170F"/>
                            <w:rsid w:val="000F1F7A"/>
                            <w:rsid w:val="000F4690"/>
                            <w:rsid w:val="000F6088"/>
                            <w:rsid w:val="00105B52"/>
                            <w:rsid w:val="00106330"/>
                            <w:rsid w:val="00106D28"/>
                            <w:rsid w:val="00111A8D"/>
                            <w:rsid w:val="0011329F"/>
                            <w:rsid w:val="001133FB"/>
                            <w:rsid w:val="0011436B"/>
                            <w:rsid w:val="00116B73"/>
                            <w:rsid w:val="00117161"/>
                            <w:rsid w:val="00121A82"/>
                            <w:rsid w:val="00122464"/>
                            <w:rsid w:val="00122EF2"/>
                            <w:rsid w:val="00122F47"/>
                            <w:rsid w:val="00124E9F"/>
                            <w:rsid w:val="00127548"/>
                            <w:rsid w:val="0013094E"/>
                            <w:rsid w:val="00131083"/>
                            <w:rsid w:val="00132FC9"/>
                            <w:rsid w:val="00133064"/>
                            <w:rsid w:val="00137EDC"/>
                            <w:rsid w:val="0014078A"/>
                            <w:rsid w:val="00140CAB"/>
                            <w:rsid w:val="0014259D"/>
                            <w:rsid w:val="00143EBB"/>
                            <w:rsid w:val="0014426C"/>
                            <w:rsid w:val="001465DC"/>
                            <w:rsid w:val="001516A6"/>
                            <w:rsid w:val="001534C7"/>
                            <w:rsid w:val="00153879"/>
                            <w:rsid w:val="00154CB2"/>
                            <w:rsid w:val="001607C5"/>
                            <w:rsid w:val="00161A02"/>
                            <w:rsid w:val="001640DD"/>
                            <w:rsid w:val="00167443"/>
                            <w:rsid w:val="0017038A"/>
                            <w:rsid w:val="00170924"/>
                            <w:rsid w:val="00172C0B"/>
                            <w:rsid w:val="0017367C"/>
                            <w:rsid w:val="00173C92"/>
                            <w:rsid w:val="0017680E"/>
                            <w:rsid w:val="00176A16"/>
                            <w:rsid w:val="00187B83"/>
                            <w:rsid w:val="00187FB6"/>
                            <w:rsid w:val="0019153B"/>
                            <w:rsid w:val="00191C27"/>
                            <w:rsid w:val="00194284"/>
                            <w:rsid w:val="001943BF"/>
                            <w:rsid w:val="00194B1F"/>
                            <w:rsid w:val="001958E1"/>
                            <w:rsid w:val="001A04C7"/>
                            <w:rsid w:val="001A268E"/>
                            <w:rsid w:val="001A2B76"/>
                            <w:rsid w:val="001A4F1F"/>
                            <w:rsid w:val="001A584B"/>
                            <w:rsid w:val="001A5A5E"/>
                            <w:rsid w:val="001A5B58"/>
                            <w:rsid w:val="001B6360"/>
                            <w:rsid w:val="001C1467"/>
                            <w:rsid w:val="001C4AF3"/>
                            <w:rsid w:val="001C61BC"/>
                            <w:rsid w:val="001D29E1"/>
                            <w:rsid w:val="001D2A17"/>
                            <w:rsid w:val="001D64A5"/>
                            <w:rsid w:val="001D66CF"/>
                            <w:rsid w:val="001E0187"/>
                            <w:rsid w:val="001E01A9"/>
                            <w:rsid w:val="001E516C"/>
                            <w:rsid w:val="001E52CE"/>
                            <w:rsid w:val="001E5521"/>
                            <w:rsid w:val="001E5F78"/>
                            <w:rsid w:val="001F07AE"/>
                            <w:rsid w:val="001F0DC6"/>
                            <w:rsid w:val="001F24B9"/>
                            <w:rsid w:val="001F29EC"/>
                            <w:rsid w:val="001F4E76"/>
                            <w:rsid w:val="001F647B"/>
                            <w:rsid w:val="001F674D"/>
                            <w:rsid w:val="001F69F7"/>
                            <w:rsid w:val="002000B5"/>
                            <w:rsid w:val="00201FB2"/>
                            <w:rsid w:val="00202789"/>
                            <w:rsid w:val="002046CD"/>
                            <w:rsid w:val="0020560F"/>
                            <w:rsid w:val="002116D1"/>
                            <w:rsid w:val="002137A9"/>
                            <w:rsid w:val="00214DC9"/>
                            <w:rsid w:val="002154EB"/>
                            <w:rsid w:val="0021641C"/>
                            <w:rsid w:val="00216F07"/>
                            <w:rsid w:val="00220234"/>
                            <w:rsid w:val="002211E5"/>
                            <w:rsid w:val="002233F6"/>
                            <w:rsid w:val="00224B2F"/>
                            <w:rsid w:val="0022693A"/>
                            <w:rsid w:val="0023405C"/>
                            <w:rsid w:val="00234A78"/>
                            <w:rsid w:val="0023638A"/>
                            <w:rsid w:val="00236CD4"/>
                            <w:rsid w:val="00243775"/>
                            <w:rsid w:val="0024409B"/>
                            <w:rsid w:val="0024470A"/>
                            <w:rsid w:val="002461C8"/>
                            <w:rsid w:val="00246FFE"/>
                            <w:rsid w:val="00247CA9"/>
                            <w:rsid w:val="00251535"/>
                            <w:rsid w:val="00252B6E"/>
                            <w:rsid w:val="002562DB"/>
                            <w:rsid w:val="002562E1"/>
                            <w:rsid w:val="0026000A"/>
                            <w:rsid w:val="00260327"/>
                            <w:rsid w:val="002616B6"/>
                            <w:rsid w:val="0026186F"/>
                            <w:rsid w:val="0026260D"/>
                            <w:rsid w:val="00262DCF"/>
                            <w:rsid w:val="002634C5"/>
                            <w:rsid w:val="0026451C"/>
                            <w:rsid w:val="0027103F"/>
                            <w:rsid w:val="0027319D"/>
                            <w:rsid w:val="002748D7"/>
                            <w:rsid w:val="00275A2F"/>
                            <w:rsid w:val="002774BC"/>
                            <w:rsid w:val="0028035F"/>
                            <w:rsid w:val="00282140"/>
                            <w:rsid w:val="0028386D"/>
                            <w:rsid w:val="0028423E"/>
                            <w:rsid w:val="00284289"/>
                            <w:rsid w:val="0028498D"/>
                            <w:rsid w:val="002907DB"/>
                            <w:rsid w:val="00290A37"/>
                            <w:rsid w:val="00290AF2"/>
                            <w:rsid w:val="002929CD"/>
                            <w:rsid w:val="00292C2E"/>
                            <w:rsid w:val="0029345C"/>
                            <w:rsid w:val="0029623F"/>
                            <w:rsid w:val="002A08E5"/>
                            <w:rsid w:val="002A3D8D"/>
                            <w:rsid w:val="002A538C"/>
                            <w:rsid w:val="002B1352"/>
                            <w:rsid w:val="002B460F"/>
                            <w:rsid w:val="002B6D32"/>
                            <w:rsid w:val="002B7E22"/>
                            <w:rsid w:val="002C0C89"/>
                            <w:rsid w:val="002C0CD1"/>
                            <w:rsid w:val="002C41D6"/>
                            <w:rsid w:val="002C6250"/>
                            <w:rsid w:val="002C6CFF"/>
                            <w:rsid w:val="002C72B9"/>
                            <w:rsid w:val="002C746C"/>
                            <w:rsid w:val="002D5367"/>
                            <w:rsid w:val="002D53C4"/>
                            <w:rsid w:val="002D585A"/>
                            <w:rsid w:val="002D5AAD"/>
                            <w:rsid w:val="002D60F5"/>
                            <w:rsid w:val="002D6CED"/>
                            <w:rsid w:val="002D7C6E"/>
                            <w:rsid w:val="002E21DF"/>
                            <w:rsid w:val="002E3695"/>
                            <w:rsid w:val="002E4840"/>
                            <w:rsid w:val="002E4F0C"/>
                            <w:rsid w:val="002E7519"/>
                            <w:rsid w:val="002F099F"/>
                            <w:rsid w:val="002F1590"/>
                            <w:rsid w:val="002F2F0C"/>
                            <w:rsid w:val="002F6B6D"/>
                            <w:rsid w:val="0030415D"/>
                            <w:rsid w:val="00304995"/>
                            <w:rsid w:val="00305903"/>
                            <w:rsid w:val="00305D42"/>
                            <w:rsid w:val="00312429"/>
                            <w:rsid w:val="003132EC"/>
                            <w:rsid w:val="0031359D"/>
                            <w:rsid w:val="00313FF7"/>
                            <w:rsid w:val="00314A6F"/>
                            <w:rsid w:val="00315255"/>
                            <w:rsid w:val="00317662"/>
                            <w:rsid w:val="00324265"/>
                            <w:rsid w:val="00324347"/>
                            <w:rsid w:val="003246D3"/>
                            <w:rsid w:val="003267FF"/>
                            <w:rsid w:val="00326E93"/>
                            <w:rsid w:val="00327931"/>
                            <w:rsid w:val="00332EFC"/>
                            <w:rsid w:val="00334B14"/>
                            <w:rsid w:val="00335714"/>
                            <w:rsid w:val="003358E7"/>
                            <w:rsid w:val="00336A81"/>
                            <w:rsid w:val="00337EF7"/>
                            <w:rsid w:val="003415D1"/>
                            <w:rsid w:val="00341C82"/>
                            <w:rsid w:val="00343BE9"/>
                            <w:rsid w:val="00344B31"/>
                            <w:rsid w:val="003458F9"/>
                            <w:rsid w:val="00345D7B"/>
                            <w:rsid w:val="00346F90"/>
                            <w:rsid w:val="003515CC"/>
                            <w:rsid w:val="00354E18"/>
                            <w:rsid w:val="003561F3"/>
                            <w:rsid w:val="00360D6D"/>
                            <w:rsid w:val="00361736"/>
                            <w:rsid w:val="003623CE"/>
                            <w:rsid w:val="00363649"/>
                            <w:rsid w:val="00370E4F"/>
                            <w:rsid w:val="00371DB5"/>
                            <w:rsid w:val="00372129"/>
                            <w:rsid w:val="00372156"/>
                            <w:rsid w:val="00375B65"/>
                            <w:rsid w:val="003761D6"/>
                            <w:rsid w:val="00376F7B"/>
                            <w:rsid w:val="00380E56"/>
                            <w:rsid w:val="00382806"/>
                            <w:rsid w:val="00382FE4"/>
                            <w:rsid w:val="0038357D"/>
                            <w:rsid w:val="003845C5"/>
                            <w:rsid w:val="00385939"/>
                            <w:rsid w:val="00385EBD"/>
                            <w:rsid w:val="003919C9"/>
                            <w:rsid w:val="00393448"/>
                            <w:rsid w:val="00393722"/>
                            <w:rsid w:val="003937BB"/>
                            <w:rsid w:val="003943EC"/>
                            <w:rsid w:val="00394891"/>
                            <w:rsid w:val="00395E12"/>
                            <w:rsid w:val="0039616C"/>
                            <w:rsid w:val="003A00ED"/>
                            <w:rsid w:val="003A19D5"/>
                            <w:rsid w:val="003A1FEB"/>
                            <w:rsid w:val="003A532B"/>
                            <w:rsid w:val="003B049A"/>
                            <w:rsid w:val="003B1FCF"/>
                            <w:rsid w:val="003B21B7"/>
                            <w:rsid w:val="003B222A"/>
                            <w:rsid w:val="003B3264"/>
                            <w:rsid w:val="003B4A96"/>
                            <w:rsid w:val="003B6293"/>
                            <w:rsid w:val="003C10DB"/>
                            <w:rsid w:val="003C1521"/>
                            <w:rsid w:val="003D122A"/>
                            <w:rsid w:val="003D5ED3"/>
                            <w:rsid w:val="003E0554"/>
                            <w:rsid w:val="003E0DC3"/>
                            <w:rsid w:val="003E2A30"/>
                            <w:rsid w:val="003E3DF2"/>
                            <w:rsid w:val="003E4099"/>
                            <w:rsid w:val="003E605C"/>
                            <w:rsid w:val="003E6DC2"/>
                            <w:rsid w:val="003E6F05"/>
                            <w:rsid w:val="003E7D9B"/>
                            <w:rsid w:val="003F0E50"/>
                            <w:rsid w:val="003F2A44"/>
                            <w:rsid w:val="003F2BE4"/>
                            <w:rsid w:val="003F4C42"/>
                            <w:rsid w:val="004025F2"/>
                            <w:rsid w:val="0040287C"/>
                            <w:rsid w:val="00403099"/>
                            <w:rsid w:val="00405081"/>
                            <w:rsid w:val="00405237"/>
                            <w:rsid w:val="00407B6B"/>
                            <w:rsid w:val="004110A2"/>
                            <w:rsid w:val="00412452"/>
                            <w:rsid w:val="004146BB"/>
                            <w:rsid w:val="0041544E"/>
                            <w:rsid w:val="004201E0"/>
                            <w:rsid w:val="004207E1"/>
                            <w:rsid w:val="00421E1E"/>
                            <w:rsid w:val="004323FB"/>
                            <w:rsid w:val="004341EB"/>
                            <w:rsid w:val="00435526"/>
                            <w:rsid w:val="004434EF"/>
                            <w:rsid w:val="0045179B"/>
                            <w:rsid w:val="004519B2"/>
                            <w:rsid w:val="00452E92"/>
                            <w:rsid w:val="00453BAB"/>
                            <w:rsid w:val="004543ED"/>
                            <w:rsid w:val="00460212"/>
                            <w:rsid w:val="0046031D"/>
                            <w:rsid w:val="00460411"/>
                            <w:rsid w:val="00462E01"/>
                            <w:rsid w:val="00462E7A"/>
                            <w:rsid w:val="00463EB9"/>
                            <w:rsid w:val="00470507"/>
                            <w:rsid w:val="00470948"/>
                            <w:rsid w:val="00471288"/>
                            <w:rsid w:val="00475E61"/>
                            <w:rsid w:val="004760E0"/>
                            <w:rsid w:val="004764AD"/>
                            <w:rsid w:val="00476D8B"/>
                            <w:rsid w:val="00485F53"/>
                            <w:rsid w:val="00492349"/>
                            <w:rsid w:val="00492D31"/>
                            <w:rsid w:val="00494D0D"/>
                            <w:rsid w:val="00497519"/>
                            <w:rsid w:val="004A5DB7"/>
                            <w:rsid w:val="004A7E44"/>
                            <w:rsid w:val="004B0B94"/>
                            <w:rsid w:val="004B150A"/>
                            <w:rsid w:val="004B4319"/>
                            <w:rsid w:val="004B4C95"/>
                            <w:rsid w:val="004B63A1"/>
                            <w:rsid w:val="004C12B1"/>
                            <w:rsid w:val="004C2809"/>
                            <w:rsid w:val="004C3005"/>
                            <w:rsid w:val="004C3B0D"/>
                            <w:rsid w:val="004C3EAC"/>
                            <w:rsid w:val="004C665E"/>
                            <w:rsid w:val="004D0C9B"/>
                            <w:rsid w:val="004D2195"/>
                            <w:rsid w:val="004D21C0"/>
                            <w:rsid w:val="004D28CF"/>
                            <w:rsid w:val="004D378B"/>
                            <w:rsid w:val="004D3BF9"/>
                            <w:rsid w:val="004D4642"/>
                            <w:rsid w:val="004E1CD0"/>
                            <w:rsid w:val="004E208C"/>
                            <w:rsid w:val="004E3BAA"/>
                            <w:rsid w:val="004E3F74"/>
                            <w:rsid w:val="004E555D"/>
                            <w:rsid w:val="004E5952"/>
                            <w:rsid w:val="004E66D2"/>
                            <w:rsid w:val="004F0383"/>
                            <w:rsid w:val="004F16AD"/>
                            <w:rsid w:val="004F183C"/>
                            <w:rsid w:val="004F38F3"/>
                            <w:rsid w:val="004F43C1"/>
                            <w:rsid w:val="005014D1"/>
                            <w:rsid w:val="005018DF"/>
                            <w:rsid w:val="00501A25"/>
                            <w:rsid w:val="00501DC7"/>
                            <w:rsid w:val="005021DC"/>
                            <w:rsid w:val="0050642B"/>
                            <w:rsid w:val="0050678F"/>
                            <w:rsid w:val="00506A83"/>
                            <w:rsid w:val="00510A74"/>
                            <w:rsid w:val="00513C6C"/>
                            <w:rsid w:val="00514797"/>
                            <w:rsid w:val="005148C3"/>
                            <w:rsid w:val="005149FF"/>
                            <w:rsid w:val="00514B50"/>
                            <w:rsid w:val="00514F79"/>
                            <w:rsid w:val="00517D0F"/>
                            <w:rsid w:val="005223B6"/>
                            <w:rsid w:val="00523E88"/>
                            <w:rsid w:val="005260B8"/>
                            <w:rsid w:val="0052691F"/>
                            <w:rsid w:val="00527D4C"/>
                            <w:rsid w:val="0053028A"/>
                            <w:rsid w:val="00530602"/>
                            <w:rsid w:val="00532994"/>
                            <w:rsid w:val="00532A7E"/>
                            <w:rsid w:val="00535706"/>
                            <w:rsid w:val="00537FB0"/>
                            <w:rsid w:val="00540AB3"/>
                            <w:rsid w:val="005417B9"/>
                            <w:rsid w:val="005430E0"/>
                            <w:rsid w:val="005514A1"/>
                            <w:rsid w:val="00551ADB"/>
                            <w:rsid w:val="00551FC1"/>
                            <w:rsid w:val="00552FDA"/>
                            <w:rsid w:val="00554F6A"/>
                            <w:rsid w:val="005552D1"/>
                            <w:rsid w:val="00556FEE"/>
                            <w:rsid w:val="005602BF"/>
                            <w:rsid w:val="005608CB"/>
                            <w:rsid w:val="00566C94"/>
                            <w:rsid w:val="0056759A"/>
                            <w:rsid w:val="00571744"/>
                            <w:rsid w:val="005723AB"/>
                            <w:rsid w:val="005725D3"/>
                            <w:rsid w:val="0057541F"/>
                            <w:rsid w:val="0057610A"/>
                            <w:rsid w:val="00576A50"/>
                            <w:rsid w:val="005770BE"/>
                            <w:rsid w:val="00577BFA"/>
                            <w:rsid w:val="00582754"/>
                            <w:rsid w:val="00583914"/>
                            <w:rsid w:val="00583A4B"/>
                            <w:rsid w:val="00584DDB"/>
                            <w:rsid w:val="005850E6"/>
                            <w:rsid w:val="00585847"/>
                            <w:rsid w:val="005915FC"/>
                            <w:rsid w:val="005916B3"/>
                            <w:rsid w:val="005918E1"/>
                            <w:rsid w:val="005943C5"/>
                            <w:rsid w:val="005A3CF1"/>
                            <w:rsid w:val="005B1D9A"/>
                            <w:rsid w:val="005B4971"/>
                            <w:rsid w:val="005B759D"/>
                            <w:rsid w:val="005C0768"/>
                            <w:rsid w:val="005C0947"/>
                            <w:rsid w:val="005C351A"/>
                            <w:rsid w:val="005C384C"/>
                            <w:rsid w:val="005C42BF"/>
                            <w:rsid w:val="005C6CB5"/>
                            <w:rsid w:val="005D17CA"/>
                            <w:rsid w:val="005D1887"/>
                            <w:rsid w:val="005D196B"/>
                            <w:rsid w:val="005D3C01"/>
                            <w:rsid w:val="005D4571"/>
                            <w:rsid w:val="005D578B"/>
                            <w:rsid w:val="005D6C21"/>
                            <w:rsid w:val="005E2012"/>
                            <w:rsid w:val="005E49C8"/>
                            <w:rsid w:val="005E76CE"/>
                            <w:rsid w:val="00600956"/>
                            <w:rsid w:val="00604157"/>
                            <w:rsid w:val="00604964"/>
                            <w:rsid w:val="00607551"/>
                            <w:rsid w:val="006078FC"/>
                            <w:rsid w:val="006148BC"/>
                            <w:rsid w:val="006156D9"/>
                            <w:rsid w:val="00615F5E"/>
                            <w:rsid w:val="00617926"/>
                            <w:rsid w:val="006204D2"/>
                            <w:rsid w:val="0062154F"/>
                            <w:rsid w:val="0062472F"/>
                            <w:rsid w:val="00624DDB"/>
                            <w:rsid w:val="00624DFF"/>
                            <w:rsid w:val="006258E8"/>
                            <w:rsid w:val="00625C2C"/>
                            <w:rsid w:val="00625F7B"/>
                            <w:rsid w:val="00630798"/>
                            <w:rsid w:val="00636F12"/>
                            <w:rsid w:val="006378A0"/>
                            <w:rsid w:val="00641BB5"/>
                            <w:rsid w:val="00641D94"/>
                            <w:rsid w:val="006438C8"/>
                            <w:rsid w:val="00646D64"/>
                            <w:rsid w:val="00647A09"/>
                            <w:rsid w:val="006501F5"/>
                            <w:rsid w:val="00651E1B"/>
                            <w:rsid w:val="00653918"/>
                            <w:rsid w:val="006540E2"/>
                            <w:rsid w:val="0065503E"/>
                            <w:rsid w:val="006567BD"/>
                            <w:rsid w:val="00657C48"/>
                            <w:rsid w:val="0066025B"/>
                            <w:rsid w:val="0066080B"/>
                            <w:rsid w:val="0066457F"/>
                            <w:rsid w:val="0066572C"/>
                            <w:rsid w:val="0067024B"/>
                            <w:rsid w:val="00670999"/>
                            <w:rsid w:val="00670FC6"/>
                            <w:rsid w:val="006768F1"/>
                            <w:rsid w:val="006850E8"/>
                            <w:rsid w:val="00693C7C"/>
                            <w:rsid w:val="00693FC1"/>
                            <w:rsid w:val="00695188"/>
                            <w:rsid w:val="0069781B"/>
                            <w:rsid w:val="006A0A24"/>
                            <w:rsid w:val="006A157F"/>
                            <w:rsid w:val="006A2728"/>
                            <w:rsid w:val="006A4A88"/>
                            <w:rsid w:val="006A7CED"/>
                            <w:rsid w:val="006A7F94"/>
                            <w:rsid w:val="006B0604"/>
                            <w:rsid w:val="006B072B"/>
                            <w:rsid w:val="006B2EB4"/>
                            <w:rsid w:val="006B3E44"/>
                            <w:rsid w:val="006B4AAA"/>
                            <w:rsid w:val="006B54C8"/>
                            <w:rsid w:val="006B58DB"/>
                            <w:rsid w:val="006B7BF7"/>
                            <w:rsid w:val="006C46AE"/>
                            <w:rsid w:val="006C7D10"/>
                            <w:rsid w:val="006D0BC9"/>
                            <w:rsid w:val="006D18CC"/>
                            <w:rsid w:val="006D1A77"/>
                            <w:rsid w:val="006D36CB"/>
                            <w:rsid w:val="006D39C5"/>
                            <w:rsid w:val="006D666A"/>
                            <w:rsid w:val="006D6FBC"/>
                            <w:rsid w:val="006D762A"/>
                            <w:rsid w:val="006E0D56"/>
                            <w:rsid w:val="006E1EE5"/>
                            <w:rsid w:val="006E2961"/>
                            <w:rsid w:val="006E4311"/>
                            <w:rsid w:val="006E4E1C"/>
                            <w:rsid w:val="006E725B"/>
                            <w:rsid w:val="006F0478"/>
                            <w:rsid w:val="006F429C"/>
                            <w:rsid w:val="006F4BAA"/>
                            <w:rsid w:val="006F7840"/>
                            <w:rsid w:val="006F7F0C"/>
                            <w:rsid w:val="007014A3"/>
                            <w:rsid w:val="00701E18"/>
                            <w:rsid w:val="007022D8"/>
                            <w:rsid w:val="00702DB6"/>
                            <w:rsid w:val="00704937"/>
                            <w:rsid w:val="00705EE5"/>
                            <w:rsid w:val="00706E1D"/>
                            <w:rsid w:val="007116E7"/>
                            <w:rsid w:val="007131DC"/>
                            <w:rsid w:val="0071595E"/>
                            <w:rsid w:val="007171EB"/>
                            <w:rsid w:val="00717695"/>
                            <w:rsid w:val="00720603"/>
                            <w:rsid w:val="00724F43"/>
                            <w:rsid w:val="00725EFE"/>
                            <w:rsid w:val="00726CA2"/>
                            <w:rsid w:val="00727C7F"/>
                            <w:rsid w:val="00727CF0"/>
                            <w:rsid w:val="00731994"/>
                            <w:rsid w:val="0073226E"/>
                            <w:rsid w:val="00734636"/>
                            <w:rsid w:val="00734FD2"/>
                            <w:rsid w:val="00735983"/>
                            <w:rsid w:val="00744AC2"/>
                            <w:rsid w:val="00744AC6"/>
                            <w:rsid w:val="0075269F"/>
                            <w:rsid w:val="00756B22"/>
                            <w:rsid w:val="00760FE2"/>
                            <w:rsid w:val="00761249"/>
                            <w:rsid w:val="00762213"/>
                            <w:rsid w:val="007626F3"/>
                            <w:rsid w:val="007634D1"/>
                            <w:rsid w:val="00765602"/>
                            <w:rsid w:val="00767B06"/>
                            <w:rsid w:val="00770997"/>
                            <w:rsid w:val="007714C5"/>
                            <w:rsid w:val="00772770"/>
                            <w:rsid w:val="00772CDC"/>
                            <w:rsid w:val="0077659F"/>
                            <w:rsid w:val="00777706"/>
                            <w:rsid w:val="0077770C"/>
                            <w:rsid w:val="00780A3E"/>
                            <w:rsid w:val="00781E38"/>
                            <w:rsid w:val="007840CE"/>
                            <w:rsid w:val="007846C2"/>
                            <w:rsid w:val="00784A75"/>
                            <w:rsid w:val="007852DF"/>
                            <w:rsid w:val="00786EAA"/>
                            <w:rsid w:val="00786F5E"/>
                            <w:rsid w:val="0078759C"/>
                            <w:rsid w:val="00787A39"/>
                            <w:rsid w:val="00790316"/>
                            <w:rsid w:val="00791642"/>
                            <w:rsid w:val="00791BA6"/>
                            <w:rsid w:val="00792A89"/>
                            <w:rsid w:val="00795D02"/>
                            <w:rsid w:val="007A5D8B"/>
                            <w:rsid w:val="007A6652"/>
                            <w:rsid w:val="007B0F68"/>
                            <w:rsid w:val="007B0FCA"/>
                            <w:rsid w:val="007B1EAD"/>
                            <w:rsid w:val="007B2C34"/>
                            <w:rsid w:val="007B32ED"/>
                            <w:rsid w:val="007B5948"/>
                            <w:rsid w:val="007B71D4"/>
                            <w:rsid w:val="007B7871"/>
                            <w:rsid w:val="007B7ED9"/>
                            <w:rsid w:val="007C1721"/>
                            <w:rsid w:val="007C1C35"/>
                            <w:rsid w:val="007C2875"/>
                            <w:rsid w:val="007C49CE"/>
                            <w:rsid w:val="007C4C7C"/>
                            <w:rsid w:val="007C726B"/>
                            <w:rsid w:val="007C7F19"/>
                            <w:rsid w:val="007D1610"/>
                            <w:rsid w:val="007D39E0"/>
                            <w:rsid w:val="007D5C87"/>
                            <w:rsid w:val="007D6A40"/>
                            <w:rsid w:val="007D6A7F"/>
                            <w:rsid w:val="007D6ACE"/>
                            <w:rsid w:val="007E3825"/>
                            <w:rsid w:val="007E3E4D"/>
                            <w:rsid w:val="007E4543"/>
                            <w:rsid w:val="007E4D52"/>
                            <w:rsid w:val="007E7183"/>
                            <w:rsid w:val="007E7DD7"/>
                            <w:rsid w:val="007E7FC2"/>
                            <w:rsid w:val="007F129F"/>
                            <w:rsid w:val="007F7690"/>
                            <w:rsid w:val="007F7D68"/>
                            <w:rsid w:val="00800B67"/>
                            <w:rsid w:val="00803457"/>
                            <w:rsid w:val="008041B9"/>
                            <w:rsid w:val="008078AD"/>
                            <w:rsid w:val="00810C97"/>
                            <w:rsid w:val="00811131"/>
                            <w:rsid w:val="0081154D"/>
                            <w:rsid w:val="00811833"/>
                            <w:rsid w:val="008119D1"/>
                            <w:rsid w:val="00812E70"/>
                            <w:rsid w:val="008134A4"/>
                            <w:rsid w:val="008166CE"/>
                            <w:rsid w:val="00820046"/>
                            <w:rsid w:val="00821ED6"/>
                            <w:rsid w:val="00822B7F"/>
                            <w:rsid w:val="00824469"/>
                            <w:rsid w:val="00824DED"/>
                            <w:rsid w:val="00825432"/>
                            <w:rsid w:val="00830024"/>
                            <w:rsid w:val="008306AF"/>
                            <w:rsid w:val="00831008"/>
                            <w:rsid w:val="00832F34"/>
                            <w:rsid w:val="0083351F"/>
                            <w:rsid w:val="0083372A"/>
                            <w:rsid w:val="008346B6"/>
                            <w:rsid w:val="00834D8E"/>
                            <w:rsid w:val="00837207"/>
                            <w:rsid w:val="00840EC8"/>
                            <w:rsid w:val="008447E6"/>
                            <w:rsid w:val="00845068"/>
                            <w:rsid w:val="00846498"/>
                            <w:rsid w:val="00854E43"/>
                            <w:rsid w:val="00856D66"/>
                            <w:rsid w:val="00857397"/>
                            <w:rsid w:val="00860CC5"/>
                            <w:rsid w:val="0086238B"/>
                            <w:rsid w:val="00863131"/>
                            <w:rsid w:val="00866975"/>
                            <w:rsid w:val="008675B0"/>
                            <w:rsid w:val="00867D7D"/>
                            <w:rsid w:val="00867EFB"/>
                            <w:rsid w:val="00870586"/>
                            <w:rsid w:val="00870B0C"/>
                            <w:rsid w:val="00871463"/>
                            <w:rsid w:val="0087255B"/>
                            <w:rsid w:val="0087375D"/>
                            <w:rsid w:val="00873DC3"/>
                            <w:rsid w:val="008768A8"/>
                            <w:rsid w:val="00876C53"/>
                            <w:rsid w:val="008818AB"/>
                            <w:rsid w:val="008843FF"/>
                            <w:rsid w:val="0088593E"/>
                            <w:rsid w:val="0088615C"/>
                            <w:rsid w:val="008862B3"/>
                            <w:rsid w:val="008867C0"/>
                            <w:rsid w:val="00890FF0"/>
                            <w:rsid w:val="00892183"/>
                            <w:rsid w:val="008922F7"/>
                            <w:rsid w:val="00892DE0"/>
                            <w:rsid w:val="008940E9"/>
                            <w:rsid w:val="00896598"/>
                            <w:rsid w:val="00896A00"/>
                            <w:rsid w:val="008A1E8E"/>
                            <w:rsid w:val="008A3A98"/>
                            <w:rsid w:val="008A516D"/>
                            <w:rsid w:val="008A6064"/>
                            <w:rsid w:val="008A7DD9"/>
                            <w:rsid w:val="008B6257"/>
                            <w:rsid w:val="008B6D57"/>
                            <w:rsid w:val="008B712C"/>
                            <w:rsid w:val="008B7729"/>
                            <w:rsid w:val="008C1138"/>
                            <w:rsid w:val="008C2929"/>
                            <w:rsid w:val="008C2E37"/>
                            <w:rsid w:val="008C5EE3"/>
                            <w:rsid w:val="008C6385"/>
                            <w:rsid w:val="008C6F9F"/>
                            <w:rsid w:val="008C7BD0"/>
                            <w:rsid w:val="008D07C3"/>
                            <w:rsid w:val="008D1736"/>
                            <w:rsid w:val="008D28AB"/>
                            <w:rsid w:val="008D3512"/>
                            <w:rsid w:val="008D372A"/>
                            <w:rsid w:val="008D4777"/>
                            <w:rsid w:val="008E0F42"/>
                            <w:rsid w:val="008E669E"/>
                            <w:rsid w:val="008E6FEC"/>
                            <w:rsid w:val="008E7282"/>
                            <w:rsid w:val="008E7D3B"/>
                            <w:rsid w:val="008F1FBB"/>
                            <w:rsid w:val="008F2A0E"/>
                            <w:rsid w:val="00900F4A"/>
                            <w:rsid w:val="009018AE"/>
                            <w:rsid w:val="009019B8"/>
                            <w:rsid w:val="009022A7"/>
                            <w:rsid w:val="00903061"/>
                            <w:rsid w:val="0090357B"/>
                            <w:rsid w:val="0090661C"/>
                            <w:rsid w:val="0091234B"/>
                            <w:rsid w:val="0091245B"/>
                            <w:rsid w:val="0091263E"/>
                            <w:rsid w:val="00912D37"/>
                            <w:rsid w:val="00912EE8"/>
                            <w:rsid w:val="00913F1B"/>
                            <w:rsid w:val="0092188A"/>
                            <w:rsid w:val="00924911"/>
                            <w:rsid w:val="00924A32"/>
                            <w:rsid w:val="00925345"/>
                            <w:rsid w:val="0092564F"/>
                            <w:rsid w:val="00925B80"/>
                            <w:rsid w:val="00932253"/>
                            <w:rsid w:val="009412D1"/>
                            <w:rsid w:val="00944896"/>
                            <w:rsid w:val="00944B2B"/>
                            <w:rsid w:val="00944D6A"/>
                            <w:rsid w:val="00947778"/>
                            <w:rsid w:val="00955D72"/>
                            <w:rsid w:val="00961C3B"/>
                            <w:rsid w:val="00964BAB"/>
                            <w:rsid w:val="00971DFC"/>
                            <w:rsid w:val="0097248B"/>
                            <w:rsid w:val="00973042"/>
                            <w:rsid w:val="00973881"/>
                            <w:rsid w:val="00976BFD"/>
                            <w:rsid w:val="00976D8B"/>
                            <w:rsid w:val="00990CCF"/>
                            <w:rsid w:val="00991122"/>
                            <w:rsid w:val="00992317"/>
                            <w:rsid w:val="00992565"/>
                            <w:rsid w:val="00993775"/>
                            <w:rsid w:val="0099437B"/>
                            <w:rsid w:val="0099766C"/>
                            <w:rsid w:val="00997A0B"/>
                            <w:rsid w:val="009A022E"/>
                            <w:rsid w:val="009A337C"/>
                            <w:rsid w:val="009A6AC5"/>
                            <w:rsid w:val="009A7854"/>
                            <w:rsid w:val="009A7B39"/>
                            <w:rsid w:val="009B0189"/>
                            <w:rsid w:val="009B0B3E"/>
                            <w:rsid w:val="009B3001"/>
                            <w:rsid w:val="009B366C"/>
                            <w:rsid w:val="009B4069"/>
                            <w:rsid w:val="009B5FE6"/>
                            <w:rsid w:val="009B62D2"/>
                            <w:rsid w:val="009B7A0F"/>
                            <w:rsid w:val="009C77A4"/>
                            <w:rsid w:val="009C7B44"/>
                            <w:rsid w:val="009D3520"/>
                            <w:rsid w:val="009D38EC"/>
                            <w:rsid w:val="009D3DD2"/>
                            <w:rsid w:val="009D6513"/>
                            <w:rsid w:val="009E050E"/>
                            <w:rsid w:val="009E086C"/>
                            <w:rsid w:val="009E278B"/>
                            <w:rsid w:val="009E27A2"/>
                            <w:rsid w:val="009E321F"/>
                            <w:rsid w:val="009E5CE7"/>
                            <w:rsid w:val="009E6751"/>
                            <w:rsid w:val="009F0425"/>
                            <w:rsid w:val="009F19A7"/>
                            <w:rsid w:val="009F229F"/>
                            <w:rsid w:val="009F632A"/>
                            <w:rsid w:val="00A006EF"/>
                            <w:rsid w:val="00A02171"/>
                            <w:rsid w:val="00A02D79"/>
                            <w:rsid w:val="00A04DAD"/>
                            <w:rsid w:val="00A061F7"/>
                            <w:rsid w:val="00A06C2D"/>
                            <w:rsid w:val="00A10DBC"/>
                            <w:rsid w:val="00A11689"/>
                            <w:rsid w:val="00A11C82"/>
                            <w:rsid w:val="00A1533F"/>
                            <w:rsid w:val="00A17269"/>
                            <w:rsid w:val="00A177D7"/>
                            <w:rsid w:val="00A1791B"/>
                            <w:rsid w:val="00A20AED"/>
                            <w:rsid w:val="00A258A5"/>
                            <w:rsid w:val="00A32133"/>
                            <w:rsid w:val="00A344D9"/>
                            <w:rsid w:val="00A3487C"/>
                            <w:rsid w:val="00A34A3B"/>
                            <w:rsid w:val="00A37B3A"/>
                            <w:rsid w:val="00A41516"/>
                            <w:rsid w:val="00A4349C"/>
                            <w:rsid w:val="00A43978"/>
                            <w:rsid w:val="00A4544C"/>
                            <w:rsid w:val="00A46960"/>
                            <w:rsid w:val="00A516F1"/>
                            <w:rsid w:val="00A562AA"/>
                            <w:rsid w:val="00A569BD"/>
                            <w:rsid w:val="00A60FDA"/>
                            <w:rsid w:val="00A61DF3"/>
                            <w:rsid w:val="00A62250"/>
                            <w:rsid w:val="00A624DF"/>
                            <w:rsid w:val="00A6297D"/>
                            <w:rsid w:val="00A63186"/>
                            <w:rsid w:val="00A648A3"/>
                            <w:rsid w:val="00A651E1"/>
                            <w:rsid w:val="00A66DA1"/>
                            <w:rsid w:val="00A70793"/>
                            <w:rsid w:val="00A745A9"/>
                            <w:rsid w:val="00A823EF"/>
                            <w:rsid w:val="00A83744"/>
                            <w:rsid w:val="00A847C2"/>
                            <w:rsid w:val="00A8529F"/>
                            <w:rsid w:val="00A8573D"/>
                            <w:rsid w:val="00A85FA0"/>
                            <w:rsid w:val="00A8734F"/>
                            <w:rsid w:val="00A9058F"/>
                            <w:rsid w:val="00A9266C"/>
                            <w:rsid w:val="00A92849"/>
                            <w:rsid w:val="00A9398C"/>
                            <w:rsid w:val="00A967FD"/>
                            <w:rsid w:val="00A96D35"/>
                            <w:rsid w:val="00A97E2F"/>
                            <w:rsid w:val="00AA186C"/>
                            <w:rsid w:val="00AA26AE"/>
                            <w:rsid w:val="00AA3ADE"/>
                            <w:rsid w:val="00AA7B93"/>
                            <w:rsid w:val="00AA7FFE"/>
                            <w:rsid w:val="00AB1FDD"/>
                            <w:rsid w:val="00AB310C"/>
                            <w:rsid w:val="00AB3B60"/>
                            <w:rsid w:val="00AB5C15"/>
                            <w:rsid w:val="00AB64AF"/>
                            <w:rsid w:val="00AB6987"/>
                            <w:rsid w:val="00AC05DC"/>
                            <w:rsid w:val="00AC2B37"/>
                            <w:rsid w:val="00AC371B"/>
                            <w:rsid w:val="00AC371D"/>
                            <w:rsid w:val="00AC3EF3"/>
                            <w:rsid w:val="00AC48B7"/>
                            <w:rsid w:val="00AC57EA"/>
                            <w:rsid w:val="00AC638F"/>
                            <w:rsid w:val="00AC6CC0"/>
                            <w:rsid w:val="00AC73A5"/>
                            <w:rsid w:val="00AD13CC"/>
                            <w:rsid w:val="00AD3EB9"/>
                            <w:rsid w:val="00AD44B4"/>
                            <w:rsid w:val="00AD513D"/>
                            <w:rsid w:val="00AD5A12"/>
                            <w:rsid w:val="00AD6A15"/>
                            <w:rsid w:val="00AE2BED"/>
                            <w:rsid w:val="00AE2E10"/>
                            <w:rsid w:val="00AE3672"/>
                            <w:rsid w:val="00AE4BE0"/>
                            <w:rsid w:val="00AE7987"/>
                            <w:rsid w:val="00AF069D"/>
                            <w:rsid w:val="00AF3318"/>
                            <w:rsid w:val="00AF4397"/>
                            <w:rsid w:val="00AF5DBA"/>
                            <w:rsid w:val="00AF7D5C"/>
                            <w:rsid w:val="00B00997"/>
                            <w:rsid w:val="00B0190E"/>
                            <w:rsid w:val="00B028CC"/>
                            <w:rsid w:val="00B04A75"/>
                            <w:rsid w:val="00B1228A"/>
                            <w:rsid w:val="00B16832"/>
                            <w:rsid w:val="00B171A5"/>
                            <w:rsid w:val="00B17315"/>
                            <w:rsid w:val="00B1758E"/>
                            <w:rsid w:val="00B17E19"/>
                            <w:rsid w:val="00B3098D"/>
                            <w:rsid w:val="00B3176F"/>
                            <w:rsid w:val="00B3187C"/>
                            <w:rsid w:val="00B32810"/>
                            <w:rsid w:val="00B333F2"/>
                            <w:rsid w:val="00B34DD9"/>
                            <w:rsid w:val="00B368DC"/>
                            <w:rsid w:val="00B3771A"/>
                            <w:rsid w:val="00B411DF"/>
                            <w:rsid w:val="00B42BB5"/>
                            <w:rsid w:val="00B44818"/>
                            <w:rsid w:val="00B449F3"/>
                            <w:rsid w:val="00B457EA"/>
                            <w:rsid w:val="00B46E61"/>
                            <w:rsid w:val="00B4757A"/>
                            <w:rsid w:val="00B51AF0"/>
                            <w:rsid w:val="00B54C30"/>
                            <w:rsid w:val="00B54E5F"/>
                            <w:rsid w:val="00B56056"/>
                            <w:rsid w:val="00B564C0"/>
                            <w:rsid w:val="00B60F3E"/>
                            <w:rsid w:val="00B61222"/>
                            <w:rsid w:val="00B61417"/>
                            <w:rsid w:val="00B62DC6"/>
                            <w:rsid w:val="00B63CB1"/>
                            <w:rsid w:val="00B64E43"/>
                            <w:rsid w:val="00B65359"/>
                            <w:rsid w:val="00B66406"/>
                            <w:rsid w:val="00B67121"/>
                            <w:rsid w:val="00B67E45"/>
                            <w:rsid w:val="00B709EA"/>
                            <w:rsid w:val="00B72C59"/>
                            <w:rsid w:val="00B750D3"/>
                            <w:rsid w:val="00B80591"/>
                            <w:rsid w:val="00B80799"/>
                            <w:rsid w:val="00B85F14"/>
                            <w:rsid w:val="00B9195B"/>
                            <w:rsid w:val="00B92C39"/>
                            <w:rsid w:val="00B93B8B"/>
                            <w:rsid w:val="00BA13FB"/>
                            <w:rsid w:val="00BA1B6B"/>
                            <w:rsid w:val="00BA1E30"/>
                            <w:rsid w:val="00BA30FE"/>
                            <w:rsid w:val="00BA4EFE"/>
                            <w:rsid w:val="00BA7788"/>
                            <w:rsid w:val="00BA7C2C"/>
                            <w:rsid w:val="00BB3A42"/>
                            <w:rsid w:val="00BB7021"/>
                            <w:rsid w:val="00BB7959"/>
                            <w:rsid w:val="00BB7F90"/>
                            <w:rsid w:val="00BC023D"/>
                            <w:rsid w:val="00BC1772"/>
                            <w:rsid w:val="00BD24EB"/>
                            <w:rsid w:val="00BD4477"/>
                            <w:rsid w:val="00BD4723"/>
                            <w:rsid w:val="00BD6275"/>
                            <w:rsid w:val="00BD712A"/>
                            <w:rsid w:val="00BE085A"/>
                            <w:rsid w:val="00BE0B01"/>
                            <w:rsid w:val="00BE1BCA"/>
                            <w:rsid w:val="00BE32F1"/>
                            <w:rsid w:val="00BE5298"/>
                            <w:rsid w:val="00BE6048"/>
                            <w:rsid w:val="00BF0E04"/>
                            <w:rsid w:val="00BF1EDB"/>
                            <w:rsid w:val="00BF29D4"/>
                            <w:rsid w:val="00BF3DC2"/>
                            <w:rsid w:val="00BF3E0D"/>
                            <w:rsid w:val="00BF5562"/>
                            <w:rsid w:val="00BF5FF9"/>
                            <w:rsid w:val="00BF71E5"/>
                            <w:rsid w:val="00C00C03"/>
                            <w:rsid w:val="00C01D34"/>
                            <w:rsid w:val="00C02A70"/>
                            <w:rsid w:val="00C02BC5"/>
                            <w:rsid w:val="00C05320"/>
                            <w:rsid w:val="00C054DB"/>
                            <w:rsid w:val="00C069B7"/>
                            <w:rsid w:val="00C1119F"/>
                            <w:rsid w:val="00C11B39"/>
                            <w:rsid w:val="00C13BD4"/>
                            <w:rsid w:val="00C20B06"/>
                            <w:rsid w:val="00C25CBC"/>
                            <w:rsid w:val="00C26869"/>
                            <w:rsid w:val="00C26B1C"/>
                            <w:rsid w:val="00C301CF"/>
                            <w:rsid w:val="00C3054F"/>
                            <w:rsid w:val="00C306E0"/>
                            <w:rsid w:val="00C31DFE"/>
                            <w:rsid w:val="00C34DAE"/>
                            <w:rsid w:val="00C359BE"/>
                            <w:rsid w:val="00C37FDF"/>
                            <w:rsid w:val="00C45C82"/>
                            <w:rsid w:val="00C46B51"/>
                            <w:rsid w:val="00C5197A"/>
                            <w:rsid w:val="00C570B1"/>
                            <w:rsid w:val="00C577F7"/>
                            <w:rsid w:val="00C57F60"/>
                            <w:rsid w:val="00C60652"/>
                            <w:rsid w:val="00C60B81"/>
                            <w:rsid w:val="00C62411"/>
                            <w:rsid w:val="00C65802"/>
                            <w:rsid w:val="00C66192"/>
                            <w:rsid w:val="00C71C36"/>
                            <w:rsid w:val="00C75940"/>
                            <w:rsid w:val="00C76631"/>
                            <w:rsid w:val="00C76E67"/>
                            <w:rsid w:val="00C9035C"/>
                            <w:rsid w:val="00C90F47"/>
                            <w:rsid w:val="00C9232A"/>
                            <w:rsid w:val="00C955DC"/>
                            <w:rsid w:val="00C95671"/>
                            <w:rsid w:val="00C970B0"/>
                            <w:rsid w:val="00CA031E"/>
                            <w:rsid w:val="00CA5306"/>
                            <w:rsid w:val="00CA7041"/>
                            <w:rsid w:val="00CB3E7F"/>
                            <w:rsid w:val="00CB6844"/>
                            <w:rsid w:val="00CB6D7B"/>
                            <w:rsid w:val="00CC2D18"/>
                            <w:rsid w:val="00CC309E"/>
                            <w:rsid w:val="00CC34CF"/>
                            <w:rsid w:val="00CC5A4D"/>
                            <w:rsid w:val="00CC5E54"/>
                            <w:rsid w:val="00CC6C2B"/>
                            <w:rsid w:val="00CD3E62"/>
                            <w:rsid w:val="00CE248E"/>
                            <w:rsid w:val="00CE5D80"/>
                            <w:rsid w:val="00CF048D"/>
                            <w:rsid w:val="00CF0C75"/>
                            <w:rsid w:val="00CF14FE"/>
                            <w:rsid w:val="00CF182A"/>
                            <w:rsid w:val="00CF2B75"/>
                            <w:rsid w:val="00CF454B"/>
                            <w:rsid w:val="00CF5B49"/>
                            <w:rsid w:val="00CF678F"/>
                            <w:rsid w:val="00CF6CA0"/>
                            <w:rsid w:val="00D009ED"/>
                            <w:rsid w:val="00D02212"/>
                            <w:rsid w:val="00D042CA"/>
                            <w:rsid w:val="00D04C4D"/>
                            <w:rsid w:val="00D05919"/>
                            <w:rsid w:val="00D05AC2"/>
                            <w:rsid w:val="00D06758"/>
                            <w:rsid w:val="00D071AE"/>
                            <w:rsid w:val="00D10B0E"/>
                            <w:rsid w:val="00D11EF3"/>
                            <w:rsid w:val="00D14F87"/>
                            <w:rsid w:val="00D15246"/>
                            <w:rsid w:val="00D155BA"/>
                            <w:rsid w:val="00D16A2B"/>
                            <w:rsid w:val="00D216C9"/>
                            <w:rsid w:val="00D30B4F"/>
                            <w:rsid w:val="00D37E8D"/>
                            <w:rsid w:val="00D416D1"/>
                            <w:rsid w:val="00D42DF0"/>
                            <w:rsid w:val="00D43441"/>
                            <w:rsid w:val="00D447FA"/>
                            <w:rsid w:val="00D44B52"/>
                            <w:rsid w:val="00D45114"/>
                            <w:rsid w:val="00D47560"/>
                            <w:rsid w:val="00D50E11"/>
                            <w:rsid w:val="00D53D81"/>
                            <w:rsid w:val="00D56018"/>
                            <w:rsid w:val="00D60C96"/>
                            <w:rsid w:val="00D65162"/>
                            <w:rsid w:val="00D66DD9"/>
                            <w:rsid w:val="00D66FE7"/>
                            <w:rsid w:val="00D675BC"/>
                            <w:rsid w:val="00D7220A"/>
                            <w:rsid w:val="00D74126"/>
                            <w:rsid w:val="00D7482C"/>
                            <w:rsid w:val="00D75DB9"/>
                            <w:rsid w:val="00D772E1"/>
                            <w:rsid w:val="00D77786"/>
                            <w:rsid w:val="00D8086B"/>
                            <w:rsid w:val="00D8219C"/>
                            <w:rsid w:val="00D85691"/>
                            <w:rsid w:val="00D85915"/>
                            <w:rsid w:val="00D94F2A"/>
                            <w:rsid w:val="00DA0831"/>
                            <w:rsid w:val="00DA0D36"/>
                            <w:rsid w:val="00DA1BCA"/>
                            <w:rsid w:val="00DA3B3C"/>
                            <w:rsid w:val="00DA72F6"/>
                            <w:rsid w:val="00DB57BF"/>
                            <w:rsid w:val="00DB5D40"/>
                            <w:rsid w:val="00DB5ED4"/>
                            <w:rsid w:val="00DB6CE8"/>
                            <w:rsid w:val="00DB7B27"/>
                            <w:rsid w:val="00DC0852"/>
                            <w:rsid w:val="00DC1D75"/>
                            <w:rsid w:val="00DC1FD5"/>
                            <w:rsid w:val="00DC3247"/>
                            <w:rsid w:val="00DD6B46"/>
                            <w:rsid w:val="00DD6CB7"/>
                            <w:rsid w:val="00DE2808"/>
                            <w:rsid w:val="00DE42DB"/>
                            <w:rsid w:val="00DE4A01"/>
                            <w:rsid w:val="00DE62DD"/>
                            <w:rsid w:val="00DE7F3F"/>
                            <w:rsid w:val="00DF0008"/>
                            <w:rsid w:val="00DF69DE"/>
                            <w:rsid w:val="00DF7D7E"/>
                            <w:rsid w:val="00E0128D"/>
                            <w:rsid w:val="00E12BFA"/>
                            <w:rsid w:val="00E15609"/>
                            <w:rsid w:val="00E15927"/>
                            <w:rsid w:val="00E165D7"/>
                            <w:rsid w:val="00E21AC4"/>
                            <w:rsid w:val="00E2282C"/>
                            <w:rsid w:val="00E236DD"/>
                            <w:rsid w:val="00E31C15"/>
                            <w:rsid w:val="00E34BF7"/>
                            <w:rsid w:val="00E35584"/>
                            <w:rsid w:val="00E36412"/>
                            <w:rsid w:val="00E379F1"/>
                            <w:rsid w:val="00E407F7"/>
                            <w:rsid w:val="00E40E6B"/>
                            <w:rsid w:val="00E40F66"/>
                            <w:rsid w:val="00E4409B"/>
                            <w:rsid w:val="00E45877"/>
                            <w:rsid w:val="00E51095"/>
                            <w:rsid w:val="00E52035"/>
                            <w:rsid w:val="00E52377"/>
                            <w:rsid w:val="00E57495"/>
                            <w:rsid w:val="00E6019D"/>
                            <w:rsid w:val="00E6090D"/>
                            <w:rsid w:val="00E61AD3"/>
                            <w:rsid w:val="00E6651E"/>
                            <w:rsid w:val="00E67971"/>
                            <w:rsid w:val="00E71658"/>
                            <w:rsid w:val="00E728DF"/>
                            <w:rsid w:val="00E73011"/>
                            <w:rsid w:val="00E74ADF"/>
                            <w:rsid w:val="00E766D6"/>
                            <w:rsid w:val="00E8347C"/>
                            <w:rsid w:val="00E842A2"/>
                            <w:rsid w:val="00E86C48"/>
                            <w:rsid w:val="00E86CBF"/>
                            <w:rsid w:val="00E903CB"/>
                            <w:rsid w:val="00E933BC"/>
                            <w:rsid w:val="00E93AC7"/>
                            <w:rsid w:val="00E948A3"/>
                            <w:rsid w:val="00E95F2E"/>
                            <w:rsid w:val="00EA1E9A"/>
                            <w:rsid w:val="00EA205E"/>
                            <w:rsid w:val="00EA40D9"/>
                            <w:rsid w:val="00EB2A77"/>
                            <w:rsid w:val="00EB37EF"/>
                            <w:rsid w:val="00EB39D7"/>
                            <w:rsid w:val="00EB4B3A"/>
                            <w:rsid w:val="00EB57D6"/>
                            <w:rsid w:val="00EB581A"/>
                            <w:rsid w:val="00EB6F98"/>
                            <w:rsid w:val="00EB7241"/>
                            <w:rsid w:val="00EC222B"/>
                            <w:rsid w:val="00EC3F64"/>
                            <w:rsid w:val="00EC50FD"/>
                            <w:rsid w:val="00EC7283"/>
                            <w:rsid w:val="00ED047D"/>
                            <w:rsid w:val="00ED1AC0"/>
                            <w:rsid w:val="00ED1F00"/>
                            <w:rsid w:val="00ED5213"/>
                            <w:rsid w:val="00ED6AD4"/>
                            <w:rsid w:val="00ED70B5"/>
                            <w:rsid w:val="00EE114A"/>
                            <w:rsid w:val="00EE2348"/>
                            <w:rsid w:val="00EE42C1"/>
                            <w:rsid w:val="00EE455A"/>
                            <w:rsid w:val="00EE4669"/>
                            <w:rsid w:val="00EE5A14"/>
                            <w:rsid w:val="00EE75C8"/>
                            <w:rsid w:val="00EF01C9"/>
                            <w:rsid w:val="00EF203F"/>
                            <w:rsid w:val="00EF207F"/>
                            <w:rsid w:val="00EF6DB2"/>
                            <w:rsid w:val="00F0133D"/>
                            <w:rsid w:val="00F02EA6"/>
                            <w:rsid w:val="00F02F0A"/>
                            <w:rsid w:val="00F03023"/>
                            <w:rsid w:val="00F04873"/>
                            <w:rsid w:val="00F058DD"/>
                            <w:rsid w:val="00F105F1"/>
                            <w:rsid w:val="00F118DC"/>
                            <w:rsid w:val="00F1254A"/>
                            <w:rsid w:val="00F12BE4"/>
                            <w:rsid w:val="00F13407"/>
                            <w:rsid w:val="00F13A66"/>
                            <w:rsid w:val="00F14AEC"/>
                            <w:rsid w:val="00F20806"/>
                            <w:rsid w:val="00F20BE9"/>
                            <w:rsid w:val="00F21B85"/>
                            <w:rsid w:val="00F223BA"/>
                            <w:rsid w:val="00F228C5"/>
                            <w:rsid w:val="00F268C2"/>
                            <w:rsid w:val="00F26AB8"/>
                            <w:rsid w:val="00F27C89"/>
                            <w:rsid w:val="00F33323"/>
                            <w:rsid w:val="00F34652"/>
                            <w:rsid w:val="00F3627B"/>
                            <w:rsid w:val="00F37CA3"/>
                            <w:rsid w:val="00F37F14"/>
                            <w:rsid w:val="00F401D3"/>
                            <w:rsid w:val="00F464FC"/>
                            <w:rsid w:val="00F46C9E"/>
                            <w:rsid w:val="00F5006A"/>
                            <w:rsid w:val="00F51BFD"/>
                            <w:rsid w:val="00F52931"/>
                            <w:rsid w:val="00F60485"/>
                            <w:rsid w:val="00F607E1"/>
                            <w:rsid w:val="00F61FA8"/>
                            <w:rsid w:val="00F62685"/>
                            <w:rsid w:val="00F62809"/>
                            <w:rsid w:val="00F63880"/>
                            <w:rsid w:val="00F65A8F"/>
                            <w:rsid w:val="00F73271"/>
                            <w:rsid w:val="00F73CD0"/>
                            <w:rsid w:val="00F769D7"/>
                            <w:rsid w:val="00F81FBA"/>
                            <w:rsid w:val="00F82053"/>
                            <w:rsid w:val="00F82100"/>
                            <w:rsid w:val="00F82B87"/>
                            <w:rsid w:val="00F83BF2"/>
                            <w:rsid w:val="00F83E6C"/>
                            <w:rsid w:val="00F853A1"/>
                            <w:rsid w:val="00F85B70"/>
                            <w:rsid w:val="00F85BDC"/>
                            <w:rsid w:val="00F86802"/>
                            <w:rsid w:val="00F92669"/>
                            <w:rsid w:val="00F92EAB"/>
                            <w:rsid w:val="00F93FF2"/>
                            <w:rsid w:val="00F96C53"/>
                            <w:rsid w:val="00F97D67"/>
                            <w:rsid w:val="00FA0061"/>
                            <w:rsid w:val="00FA10DB"/>
                            <w:rsid w:val="00FA7EEB"/>
                            <w:rsid w:val="00FB77C5"/>
                            <w:rsid w:val="00FB7AEE"/>
                            <w:rsid w:val="00FC54AB"/>
                            <w:rsid w:val="00FC64F3"/>
                            <w:rsid w:val="00FD3A29"/>
                            <w:rsid w:val="00FD402F"/>
                            <w:rsid w:val="00FD5C37"/>
                            <w:rsid w:val="00FD6E5F"/>
                            <w:rsid w:val="00FE0FEB"/>
                            <w:rsid w:val="00FE3446"/>
                            <w:rsid w:val="00FE4B80"/>
                            <w:rsid w:val="00FE55C9"/>
                            <w:rsid w:val="00FE59EB"/>
                            <w:rsid w:val="00FF192E"/>
                            <w:rsid w:val="00FF1B70"/>
                            <w:rsid w:val="00FF2659"/>
                            <w:rsid w:val="00FF308C"/>
                        </w:rsids>
                        <m:mathPr>
                            <m:mathFont m:val="Cambria Math"/>
                            <m:brkBin m:val="before"/>
                            <m:brkBinSub m:val="--"/>
                            <m:smallFrac m:val="0"/>
                            <m:dispDef/>
                            <m:lMargin m:val="0"/>
                            <m:rMargin m:val="0"/>
                            <m:defJc m:val="centerGroup"/>
                            <m:wrapIndent m:val="1440"/>
                            <m:intLim m:val="subSup"/>
                            <m:naryLim m:val="undOvr"/>
                        </m:mathPr>
                        <w:themeFontLang w:val="en-US" w:eastAsia="zh-CN"/>
                        <w:clrSchemeMapping w:bg1="light1" w:t1="dark1" w:bg2="light2" w:t2="dark2" w:accent1="accent1" w:accent2="accent2" w:accent3="accent3" w:accent4="accent4" w:accent5="accent5" w:accent6="accent6" w:hyperlink="hyperlink" w:followedHyperlink="followedHyperlink"/>
                        <w:shapeDefaults>
                            <o:shapedefaults v:ext="edit" spidmax="1026"/>
                            <o:shapelayout v:ext="edit">
                                <o:idmap v:ext="edit" data="1"/>
                            </o:shapelayout>
                        </w:shapeDefaults>
                        <w:decimalSymbol w:val="."/>
                        <w:listSeparator w:val=","/>
                        <w14:docId w14:val="38382637"/>
                        <w15:chartTrackingRefBased/>
                        <w15:docId w15:val="{BB9DF462-DA59-47D7-9770-F617949E3246}"/>
                    </w:settings>
                </pkg:xmlData>
            </pkg:part>
            <pkg:part pkg:name="/customXml/item1.xml" pkg:contentType="application/xml" pkg:padding="32">
                <pkg:xmlData pkg:originalXmlStandalone="no">
                    <b:Sources
                        xmlns:b="http://schemas.openxmlformats.org/officeDocument/2006/bibliography"
                        xmlns="http://schemas.openxmlformats.org/officeDocument/2006/bibliography" SelectedStyle="\APASixthEditionOfficeOnline.xsl" StyleName="APA" Version="6"/>
                    </pkg:xmlData>
                </pkg:part>
                <pkg:part pkg:name="/customXml/itemProps1.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.customXmlProperties+xml" pkg:padding="32">
                    <pkg:xmlData pkg:originalXmlStandalone="no">
                        <ds:datastoreItem ds:itemID="{EF580B1D-203E-48FE-BBF1-A4CB2648F0E3}"
                            xmlns:ds="http://schemas.openxmlformats.org/officeDocument/2006/customXml">
                            <ds:schemaRefs>
                                <ds:schemaRef ds:uri="http://schemas.openxmlformats.org/officeDocument/2006/bibliography"/>
                            </ds:schemaRefs>
                        </ds:datastoreItem>
                    </pkg:xmlData>
                </pkg:part>
                <pkg:part pkg:name="/word/numbering.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.numbering+xml">
                    <pkg:xmlData>
                        <w:numbering
                            xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
                            xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
                            xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex"
                            xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex"
                            xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex"
                            xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex"
                            xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex"
                            xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex"
                            xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex"
                            xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex"
                            xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                            xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink"
                            xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d"
                            xmlns:o="urn:schemas-microsoft-com:office:office"
                            xmlns:oel="http://schemas.microsoft.com/office/2019/extlst"
                            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                            xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                            xmlns:v="urn:schemas-microsoft-com:vml"
                            xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
                            xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                            xmlns:w10="urn:schemas-microsoft-com:office:word"
                            xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                            xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                            xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                            xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                            xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                            xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                            xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                            xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
                            xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
                            xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
                            xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                            xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
                            <w:abstractNum w:abstractNumId="0" w15:restartNumberingAfterBreak="0">
                                <w:nsid w:val="07A2034E"/>
                                <w:multiLevelType w:val="singleLevel"/>
                                <w:tmpl w:val="08E48C5A"/>
                                <w:lvl w:ilvl="0">
                                    <w:start w:val="1"/>
                                    <w:numFmt w:val="chineseCountingThousand"/>
                                    <w:suff w:val="nothing"/>
                                    <w:lvlText w:val="%1、"/>
                                    <w:lvlJc w:val="left"/>
                                    <w:pPr>
                                        <w:ind w:left="0" w:firstLine="0"/>
                                    </w:pPr>
                                </w:lvl>
                            </w:abstractNum>
                            <w:abstractNum w:abstractNumId="1" w15:restartNumberingAfterBreak="0">
                                <w:nsid w:val="74B34D02"/>
                                <w:multiLevelType w:val="singleLevel"/>
                                <w:tmpl w:val="08E48C5A"/>
                                <w:lvl w:ilvl="0">
                                    <w:start w:val="1"/>
                                    <w:numFmt w:val="chineseCountingThousand"/>
                                    <w:suff w:val="nothing"/>
                                    <w:lvlText w:val="%1、"/>
                                    <w:lvlJc w:val="left"/>
                                    <w:pPr>
                                        <w:ind w:left="0" w:firstLine="0"/>
                                    </w:pPr>
                                </w:lvl>
                            </w:abstractNum>
                            <w:num w:numId="1" w16cid:durableId="2147353135">
                                <w:abstractNumId w:val="0"/>
                            </w:num>
                            <w:num w:numId="2" w16cid:durableId="654380942">
                                <w:abstractNumId w:val="1"/>
                            </w:num>
                        </w:numbering>
                    </pkg:xmlData>
                </pkg:part>
                <pkg:part pkg:name="/word/styles.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml">
                    <pkg:xmlData>
                        <w:styles
                            xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                            xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                            xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                            xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                            xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                            xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                            xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                            xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                            xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh">
                            <w:docDefaults>
                                <w:rPrDefault>
                                    <w:rPr>
                                        <w:rFonts w:asciiTheme="minorHAnsi" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorHAnsi" w:cstheme="minorBidi"/>
                                        <w:kern w:val="2"/>
                                        <w:sz w:val="21"/>
                                        <w:szCs w:val="22"/>
                                        <w:lang w:val="en-US" w:eastAsia="zh-CN" w:bidi="ar-SA"/>
                                    </w:rPr>
                                </w:rPrDefault>
                                <w:pPrDefault/>
                            </w:docDefaults>
                            <w:latentStyles w:defLockedState="0" w:defUIPriority="99" w:defSemiHidden="0" w:defUnhideWhenUsed="0" w:defQFormat="0" w:count="376">
                                <w:lsdException w:name="Normal" w:uiPriority="0" w:qFormat="1"/>
                                <w:lsdException w:name="heading 1" w:uiPriority="9" w:qFormat="1"/>
                                <w:lsdException w:name="heading 2" w:semiHidden="1" w:uiPriority="9" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="heading 3" w:semiHidden="1" w:uiPriority="9" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="heading 4" w:semiHidden="1" w:uiPriority="9" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="heading 5" w:semiHidden="1" w:uiPriority="9" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="heading 6" w:semiHidden="1" w:uiPriority="9" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="heading 7" w:semiHidden="1" w:uiPriority="9" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="heading 8" w:semiHidden="1" w:uiPriority="9" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="heading 9" w:semiHidden="1" w:uiPriority="9" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="index 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="index 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="index 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="index 4" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="index 5" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="index 6" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="index 7" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="index 8" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="index 9" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toc 1" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toc 2" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toc 3" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toc 4" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toc 5" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toc 6" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toc 7" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toc 8" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toc 9" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Normal Indent" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="footnote text" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="annotation text" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="header" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="footer" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="index heading" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="caption" w:semiHidden="1" w:uiPriority="35" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="table of figures" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="envelope address" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="envelope return" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="footnote reference" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="annotation reference" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="line number" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="page number" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="endnote reference" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="endnote text" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="table of authorities" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="macro" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="toa heading" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Bullet" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Number" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List 4" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List 5" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Bullet 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Bullet 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Bullet 4" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Bullet 5" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Number 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Number 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Number 4" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Number 5" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Title" w:uiPriority="10" w:qFormat="1"/>
                                <w:lsdException w:name="Closing" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Signature" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Default Paragraph Font" w:semiHidden="1" w:uiPriority="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Body Text" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Body Text Indent" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Continue" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Continue 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Continue 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Continue 4" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="List Continue 5" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Message Header" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Subtitle" w:uiPriority="11" w:qFormat="1"/>
                                <w:lsdException w:name="Salutation" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Date" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Body Text First Indent" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Body Text First Indent 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Note Heading" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Body Text 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Body Text 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Body Text Indent 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Body Text Indent 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Block Text" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Hyperlink" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="FollowedHyperlink" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Strong" w:uiPriority="22" w:qFormat="1"/>
                                <w:lsdException w:name="Emphasis" w:uiPriority="20" w:qFormat="1"/>
                                <w:lsdException w:name="Document Map" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Plain Text" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="E-mail Signature" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Top of Form" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Bottom of Form" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Normal (Web)" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Acronym" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Address" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Cite" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Code" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Definition" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Keyboard" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Preformatted" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Sample" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Typewriter" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="HTML Variable" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Normal Table" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="annotation subject" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="No List" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Outline List 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Outline List 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Outline List 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Simple 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Simple 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Simple 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Classic 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Classic 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Classic 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Classic 4" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Colorful 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Colorful 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Colorful 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Columns 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Columns 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Columns 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Columns 4" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Columns 5" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Grid 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Grid 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Grid 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Grid 4" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Grid 5" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Grid 6" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Grid 7" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Grid 8" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table List 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table List 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table List 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table List 4" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table List 5" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table List 6" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table List 7" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table List 8" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table 3D effects 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table 3D effects 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table 3D effects 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Contemporary" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Elegant" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Professional" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Subtle 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Subtle 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Web 1" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Web 2" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Web 3" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Balloon Text" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Table Grid" w:uiPriority="39"/>
                                <w:lsdException w:name="Table Theme" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Placeholder Text" w:semiHidden="1"/>
                                <w:lsdException w:name="No Spacing" w:uiPriority="1" w:qFormat="1"/>
                                <w:lsdException w:name="Light Shading" w:uiPriority="60"/>
                                <w:lsdException w:name="Light List" w:uiPriority="61"/>
                                <w:lsdException w:name="Light Grid" w:uiPriority="62"/>
                                <w:lsdException w:name="Medium Shading 1" w:uiPriority="63"/>
                                <w:lsdException w:name="Medium Shading 2" w:uiPriority="64"/>
                                <w:lsdException w:name="Medium List 1" w:uiPriority="65"/>
                                <w:lsdException w:name="Medium List 2" w:uiPriority="66"/>
                                <w:lsdException w:name="Medium Grid 1" w:uiPriority="67"/>
                                <w:lsdException w:name="Medium Grid 2" w:uiPriority="68"/>
                                <w:lsdException w:name="Medium Grid 3" w:uiPriority="69"/>
                                <w:lsdException w:name="Dark List" w:uiPriority="70"/>
                                <w:lsdException w:name="Colorful Shading" w:uiPriority="71"/>
                                <w:lsdException w:name="Colorful List" w:uiPriority="72"/>
                                <w:lsdException w:name="Colorful Grid" w:uiPriority="73"/>
                                <w:lsdException w:name="Light Shading Accent 1" w:uiPriority="60"/>
                                <w:lsdException w:name="Light List Accent 1" w:uiPriority="61"/>
                                <w:lsdException w:name="Light Grid Accent 1" w:uiPriority="62"/>
                                <w:lsdException w:name="Medium Shading 1 Accent 1" w:uiPriority="63"/>
                                <w:lsdException w:name="Medium Shading 2 Accent 1" w:uiPriority="64"/>
                                <w:lsdException w:name="Medium List 1 Accent 1" w:uiPriority="65"/>
                                <w:lsdException w:name="Revision" w:semiHidden="1"/>
                                <w:lsdException w:name="List Paragraph" w:uiPriority="34" w:qFormat="1"/>
                                <w:lsdException w:name="Quote" w:uiPriority="29" w:qFormat="1"/>
                                <w:lsdException w:name="Intense Quote" w:uiPriority="30" w:qFormat="1"/>
                                <w:lsdException w:name="Medium List 2 Accent 1" w:uiPriority="66"/>
                                <w:lsdException w:name="Medium Grid 1 Accent 1" w:uiPriority="67"/>
                                <w:lsdException w:name="Medium Grid 2 Accent 1" w:uiPriority="68"/>
                                <w:lsdException w:name="Medium Grid 3 Accent 1" w:uiPriority="69"/>
                                <w:lsdException w:name="Dark List Accent 1" w:uiPriority="70"/>
                                <w:lsdException w:name="Colorful Shading Accent 1" w:uiPriority="71"/>
                                <w:lsdException w:name="Colorful List Accent 1" w:uiPriority="72"/>
                                <w:lsdException w:name="Colorful Grid Accent 1" w:uiPriority="73"/>
                                <w:lsdException w:name="Light Shading Accent 2" w:uiPriority="60"/>
                                <w:lsdException w:name="Light List Accent 2" w:uiPriority="61"/>
                                <w:lsdException w:name="Light Grid Accent 2" w:uiPriority="62"/>
                                <w:lsdException w:name="Medium Shading 1 Accent 2" w:uiPriority="63"/>
                                <w:lsdException w:name="Medium Shading 2 Accent 2" w:uiPriority="64"/>
                                <w:lsdException w:name="Medium List 1 Accent 2" w:uiPriority="65"/>
                                <w:lsdException w:name="Medium List 2 Accent 2" w:uiPriority="66"/>
                                <w:lsdException w:name="Medium Grid 1 Accent 2" w:uiPriority="67"/>
                                <w:lsdException w:name="Medium Grid 2 Accent 2" w:uiPriority="68"/>
                                <w:lsdException w:name="Medium Grid 3 Accent 2" w:uiPriority="69"/>
                                <w:lsdException w:name="Dark List Accent 2" w:uiPriority="70"/>
                                <w:lsdException w:name="Colorful Shading Accent 2" w:uiPriority="71"/>
                                <w:lsdException w:name="Colorful List Accent 2" w:uiPriority="72"/>
                                <w:lsdException w:name="Colorful Grid Accent 2" w:uiPriority="73"/>
                                <w:lsdException w:name="Light Shading Accent 3" w:uiPriority="60"/>
                                <w:lsdException w:name="Light List Accent 3" w:uiPriority="61"/>
                                <w:lsdException w:name="Light Grid Accent 3" w:uiPriority="62"/>
                                <w:lsdException w:name="Medium Shading 1 Accent 3" w:uiPriority="63"/>
                                <w:lsdException w:name="Medium Shading 2 Accent 3" w:uiPriority="64"/>
                                <w:lsdException w:name="Medium List 1 Accent 3" w:uiPriority="65"/>
                                <w:lsdException w:name="Medium List 2 Accent 3" w:uiPriority="66"/>
                                <w:lsdException w:name="Medium Grid 1 Accent 3" w:uiPriority="67"/>
                                <w:lsdException w:name="Medium Grid 2 Accent 3" w:uiPriority="68"/>
                                <w:lsdException w:name="Medium Grid 3 Accent 3" w:uiPriority="69"/>
                                <w:lsdException w:name="Dark List Accent 3" w:uiPriority="70"/>
                                <w:lsdException w:name="Colorful Shading Accent 3" w:uiPriority="71"/>
                                <w:lsdException w:name="Colorful List Accent 3" w:uiPriority="72"/>
                                <w:lsdException w:name="Colorful Grid Accent 3" w:uiPriority="73"/>
                                <w:lsdException w:name="Light Shading Accent 4" w:uiPriority="60"/>
                                <w:lsdException w:name="Light List Accent 4" w:uiPriority="61"/>
                                <w:lsdException w:name="Light Grid Accent 4" w:uiPriority="62"/>
                                <w:lsdException w:name="Medium Shading 1 Accent 4" w:uiPriority="63"/>
                                <w:lsdException w:name="Medium Shading 2 Accent 4" w:uiPriority="64"/>
                                <w:lsdException w:name="Medium List 1 Accent 4" w:uiPriority="65"/>
                                <w:lsdException w:name="Medium List 2 Accent 4" w:uiPriority="66"/>
                                <w:lsdException w:name="Medium Grid 1 Accent 4" w:uiPriority="67"/>
                                <w:lsdException w:name="Medium Grid 2 Accent 4" w:uiPriority="68"/>
                                <w:lsdException w:name="Medium Grid 3 Accent 4" w:uiPriority="69"/>
                                <w:lsdException w:name="Dark List Accent 4" w:uiPriority="70"/>
                                <w:lsdException w:name="Colorful Shading Accent 4" w:uiPriority="71"/>
                                <w:lsdException w:name="Colorful List Accent 4" w:uiPriority="72"/>
                                <w:lsdException w:name="Colorful Grid Accent 4" w:uiPriority="73"/>
                                <w:lsdException w:name="Light Shading Accent 5" w:uiPriority="60"/>
                                <w:lsdException w:name="Light List Accent 5" w:uiPriority="61"/>
                                <w:lsdException w:name="Light Grid Accent 5" w:uiPriority="62"/>
                                <w:lsdException w:name="Medium Shading 1 Accent 5" w:uiPriority="63"/>
                                <w:lsdException w:name="Medium Shading 2 Accent 5" w:uiPriority="64"/>
                                <w:lsdException w:name="Medium List 1 Accent 5" w:uiPriority="65"/>
                                <w:lsdException w:name="Medium List 2 Accent 5" w:uiPriority="66"/>
                                <w:lsdException w:name="Medium Grid 1 Accent 5" w:uiPriority="67"/>
                                <w:lsdException w:name="Medium Grid 2 Accent 5" w:uiPriority="68"/>
                                <w:lsdException w:name="Medium Grid 3 Accent 5" w:uiPriority="69"/>
                                <w:lsdException w:name="Dark List Accent 5" w:uiPriority="70"/>
                                <w:lsdException w:name="Colorful Shading Accent 5" w:uiPriority="71"/>
                                <w:lsdException w:name="Colorful List Accent 5" w:uiPriority="72"/>
                                <w:lsdException w:name="Colorful Grid Accent 5" w:uiPriority="73"/>
                                <w:lsdException w:name="Light Shading Accent 6" w:uiPriority="60"/>
                                <w:lsdException w:name="Light List Accent 6" w:uiPriority="61"/>
                                <w:lsdException w:name="Light Grid Accent 6" w:uiPriority="62"/>
                                <w:lsdException w:name="Medium Shading 1 Accent 6" w:uiPriority="63"/>
                                <w:lsdException w:name="Medium Shading 2 Accent 6" w:uiPriority="64"/>
                                <w:lsdException w:name="Medium List 1 Accent 6" w:uiPriority="65"/>
                                <w:lsdException w:name="Medium List 2 Accent 6" w:uiPriority="66"/>
                                <w:lsdException w:name="Medium Grid 1 Accent 6" w:uiPriority="67"/>
                                <w:lsdException w:name="Medium Grid 2 Accent 6" w:uiPriority="68"/>
                                <w:lsdException w:name="Medium Grid 3 Accent 6" w:uiPriority="69"/>
                                <w:lsdException w:name="Dark List Accent 6" w:uiPriority="70"/>
                                <w:lsdException w:name="Colorful Shading Accent 6" w:uiPriority="71"/>
                                <w:lsdException w:name="Colorful List Accent 6" w:uiPriority="72"/>
                                <w:lsdException w:name="Colorful Grid Accent 6" w:uiPriority="73"/>
                                <w:lsdException w:name="Subtle Emphasis" w:uiPriority="19" w:qFormat="1"/>
                                <w:lsdException w:name="Intense Emphasis" w:uiPriority="21" w:qFormat="1"/>
                                <w:lsdException w:name="Subtle Reference" w:uiPriority="31" w:qFormat="1"/>
                                <w:lsdException w:name="Intense Reference" w:uiPriority="32" w:qFormat="1"/>
                                <w:lsdException w:name="Book Title" w:uiPriority="33" w:qFormat="1"/>
                                <w:lsdException w:name="Bibliography" w:semiHidden="1" w:uiPriority="37" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="TOC Heading" w:semiHidden="1" w:uiPriority="39" w:unhideWhenUsed="1" w:qFormat="1"/>
                                <w:lsdException w:name="Plain Table 1" w:uiPriority="41"/>
                                <w:lsdException w:name="Plain Table 2" w:uiPriority="42"/>
                                <w:lsdException w:name="Plain Table 3" w:uiPriority="43"/>
                                <w:lsdException w:name="Plain Table 4" w:uiPriority="44"/>
                                <w:lsdException w:name="Plain Table 5" w:uiPriority="45"/>
                                <w:lsdException w:name="Grid Table Light" w:uiPriority="40"/>
                                <w:lsdException w:name="Grid Table 1 Light" w:uiPriority="46"/>
                                <w:lsdException w:name="Grid Table 2" w:uiPriority="47"/>
                                <w:lsdException w:name="Grid Table 3" w:uiPriority="48"/>
                                <w:lsdException w:name="Grid Table 4" w:uiPriority="49"/>
                                <w:lsdException w:name="Grid Table 5 Dark" w:uiPriority="50"/>
                                <w:lsdException w:name="Grid Table 6 Colorful" w:uiPriority="51"/>
                                <w:lsdException w:name="Grid Table 7 Colorful" w:uiPriority="52"/>
                                <w:lsdException w:name="Grid Table 1 Light Accent 1" w:uiPriority="46"/>
                                <w:lsdException w:name="Grid Table 2 Accent 1" w:uiPriority="47"/>
                                <w:lsdException w:name="Grid Table 3 Accent 1" w:uiPriority="48"/>
                                <w:lsdException w:name="Grid Table 4 Accent 1" w:uiPriority="49"/>
                                <w:lsdException w:name="Grid Table 5 Dark Accent 1" w:uiPriority="50"/>
                                <w:lsdException w:name="Grid Table 6 Colorful Accent 1" w:uiPriority="51"/>
                                <w:lsdException w:name="Grid Table 7 Colorful Accent 1" w:uiPriority="52"/>
                                <w:lsdException w:name="Grid Table 1 Light Accent 2" w:uiPriority="46"/>
                                <w:lsdException w:name="Grid Table 2 Accent 2" w:uiPriority="47"/>
                                <w:lsdException w:name="Grid Table 3 Accent 2" w:uiPriority="48"/>
                                <w:lsdException w:name="Grid Table 4 Accent 2" w:uiPriority="49"/>
                                <w:lsdException w:name="Grid Table 5 Dark Accent 2" w:uiPriority="50"/>
                                <w:lsdException w:name="Grid Table 6 Colorful Accent 2" w:uiPriority="51"/>
                                <w:lsdException w:name="Grid Table 7 Colorful Accent 2" w:uiPriority="52"/>
                                <w:lsdException w:name="Grid Table 1 Light Accent 3" w:uiPriority="46"/>
                                <w:lsdException w:name="Grid Table 2 Accent 3" w:uiPriority="47"/>
                                <w:lsdException w:name="Grid Table 3 Accent 3" w:uiPriority="48"/>
                                <w:lsdException w:name="Grid Table 4 Accent 3" w:uiPriority="49"/>
                                <w:lsdException w:name="Grid Table 5 Dark Accent 3" w:uiPriority="50"/>
                                <w:lsdException w:name="Grid Table 6 Colorful Accent 3" w:uiPriority="51"/>
                                <w:lsdException w:name="Grid Table 7 Colorful Accent 3" w:uiPriority="52"/>
                                <w:lsdException w:name="Grid Table 1 Light Accent 4" w:uiPriority="46"/>
                                <w:lsdException w:name="Grid Table 2 Accent 4" w:uiPriority="47"/>
                                <w:lsdException w:name="Grid Table 3 Accent 4" w:uiPriority="48"/>
                                <w:lsdException w:name="Grid Table 4 Accent 4" w:uiPriority="49"/>
                                <w:lsdException w:name="Grid Table 5 Dark Accent 4" w:uiPriority="50"/>
                                <w:lsdException w:name="Grid Table 6 Colorful Accent 4" w:uiPriority="51"/>
                                <w:lsdException w:name="Grid Table 7 Colorful Accent 4" w:uiPriority="52"/>
                                <w:lsdException w:name="Grid Table 1 Light Accent 5" w:uiPriority="46"/>
                                <w:lsdException w:name="Grid Table 2 Accent 5" w:uiPriority="47"/>
                                <w:lsdException w:name="Grid Table 3 Accent 5" w:uiPriority="48"/>
                                <w:lsdException w:name="Grid Table 4 Accent 5" w:uiPriority="49"/>
                                <w:lsdException w:name="Grid Table 5 Dark Accent 5" w:uiPriority="50"/>
                                <w:lsdException w:name="Grid Table 6 Colorful Accent 5" w:uiPriority="51"/>
                                <w:lsdException w:name="Grid Table 7 Colorful Accent 5" w:uiPriority="52"/>
                                <w:lsdException w:name="Grid Table 1 Light Accent 6" w:uiPriority="46"/>
                                <w:lsdException w:name="Grid Table 2 Accent 6" w:uiPriority="47"/>
                                <w:lsdException w:name="Grid Table 3 Accent 6" w:uiPriority="48"/>
                                <w:lsdException w:name="Grid Table 4 Accent 6" w:uiPriority="49"/>
                                <w:lsdException w:name="Grid Table 5 Dark Accent 6" w:uiPriority="50"/>
                                <w:lsdException w:name="Grid Table 6 Colorful Accent 6" w:uiPriority="51"/>
                                <w:lsdException w:name="Grid Table 7 Colorful Accent 6" w:uiPriority="52"/>
                                <w:lsdException w:name="List Table 1 Light" w:uiPriority="46"/>
                                <w:lsdException w:name="List Table 2" w:uiPriority="47"/>
                                <w:lsdException w:name="List Table 3" w:uiPriority="48"/>
                                <w:lsdException w:name="List Table 4" w:uiPriority="49"/>
                                <w:lsdException w:name="List Table 5 Dark" w:uiPriority="50"/>
                                <w:lsdException w:name="List Table 6 Colorful" w:uiPriority="51"/>
                                <w:lsdException w:name="List Table 7 Colorful" w:uiPriority="52"/>
                                <w:lsdException w:name="List Table 1 Light Accent 1" w:uiPriority="46"/>
                                <w:lsdException w:name="List Table 2 Accent 1" w:uiPriority="47"/>
                                <w:lsdException w:name="List Table 3 Accent 1" w:uiPriority="48"/>
                                <w:lsdException w:name="List Table 4 Accent 1" w:uiPriority="49"/>
                                <w:lsdException w:name="List Table 5 Dark Accent 1" w:uiPriority="50"/>
                                <w:lsdException w:name="List Table 6 Colorful Accent 1" w:uiPriority="51"/>
                                <w:lsdException w:name="List Table 7 Colorful Accent 1" w:uiPriority="52"/>
                                <w:lsdException w:name="List Table 1 Light Accent 2" w:uiPriority="46"/>
                                <w:lsdException w:name="List Table 2 Accent 2" w:uiPriority="47"/>
                                <w:lsdException w:name="List Table 3 Accent 2" w:uiPriority="48"/>
                                <w:lsdException w:name="List Table 4 Accent 2" w:uiPriority="49"/>
                                <w:lsdException w:name="List Table 5 Dark Accent 2" w:uiPriority="50"/>
                                <w:lsdException w:name="List Table 6 Colorful Accent 2" w:uiPriority="51"/>
                                <w:lsdException w:name="List Table 7 Colorful Accent 2" w:uiPriority="52"/>
                                <w:lsdException w:name="List Table 1 Light Accent 3" w:uiPriority="46"/>
                                <w:lsdException w:name="List Table 2 Accent 3" w:uiPriority="47"/>
                                <w:lsdException w:name="List Table 3 Accent 3" w:uiPriority="48"/>
                                <w:lsdException w:name="List Table 4 Accent 3" w:uiPriority="49"/>
                                <w:lsdException w:name="List Table 5 Dark Accent 3" w:uiPriority="50"/>
                                <w:lsdException w:name="List Table 6 Colorful Accent 3" w:uiPriority="51"/>
                                <w:lsdException w:name="List Table 7 Colorful Accent 3" w:uiPriority="52"/>
                                <w:lsdException w:name="List Table 1 Light Accent 4" w:uiPriority="46"/>
                                <w:lsdException w:name="List Table 2 Accent 4" w:uiPriority="47"/>
                                <w:lsdException w:name="List Table 3 Accent 4" w:uiPriority="48"/>
                                <w:lsdException w:name="List Table 4 Accent 4" w:uiPriority="49"/>
                                <w:lsdException w:name="List Table 5 Dark Accent 4" w:uiPriority="50"/>
                                <w:lsdException w:name="List Table 6 Colorful Accent 4" w:uiPriority="51"/>
                                <w:lsdException w:name="List Table 7 Colorful Accent 4" w:uiPriority="52"/>
                                <w:lsdException w:name="List Table 1 Light Accent 5" w:uiPriority="46"/>
                                <w:lsdException w:name="List Table 2 Accent 5" w:uiPriority="47"/>
                                <w:lsdException w:name="List Table 3 Accent 5" w:uiPriority="48"/>
                                <w:lsdException w:name="List Table 4 Accent 5" w:uiPriority="49"/>
                                <w:lsdException w:name="List Table 5 Dark Accent 5" w:uiPriority="50"/>
                                <w:lsdException w:name="List Table 6 Colorful Accent 5" w:uiPriority="51"/>
                                <w:lsdException w:name="List Table 7 Colorful Accent 5" w:uiPriority="52"/>
                                <w:lsdException w:name="List Table 1 Light Accent 6" w:uiPriority="46"/>
                                <w:lsdException w:name="List Table 2 Accent 6" w:uiPriority="47"/>
                                <w:lsdException w:name="List Table 3 Accent 6" w:uiPriority="48"/>
                                <w:lsdException w:name="List Table 4 Accent 6" w:uiPriority="49"/>
                                <w:lsdException w:name="List Table 5 Dark Accent 6" w:uiPriority="50"/>
                                <w:lsdException w:name="List Table 6 Colorful Accent 6" w:uiPriority="51"/>
                                <w:lsdException w:name="List Table 7 Colorful Accent 6" w:uiPriority="52"/>
                                <w:lsdException w:name="Mention" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Smart Hyperlink" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Hashtag" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Unresolved Mention" w:semiHidden="1" w:unhideWhenUsed="1"/>
                                <w:lsdException w:name="Smart Link" w:semiHidden="1" w:unhideWhenUsed="1"/>
                            </w:latentStyles>
                            <w:style w:type="paragraph" w:default="1" w:styleId="a">
                                <w:name w:val="Normal"/>
                                <w:qFormat/>
                                <w:pPr>
                                    <w:widowControl w:val="0"/>
                                    <w:jc w:val="both"/>
                                </w:pPr>
                            </w:style>
                            <w:style w:type="paragraph" w:styleId="1">
                                <w:name w:val="heading 1"/>
                                <w:basedOn w:val="a"/>
                                <w:next w:val="a"/>
                                <w:link w:val="10"/>
                                <w:uiPriority w:val="9"/>
                                <w:qFormat/>
                                <w:rsid w:val="0026451C"/>
                                <w:pPr>
                                    <w:keepNext/>
                                    <w:keepLines/>
                                    <w:spacing w:before="340" w:after="330" w:line="578" w:lineRule="auto"/>
                                    <w:outlineLvl w:val="0"/>
                                </w:pPr>
                                <w:rPr>
                                    <w:b/>
                                    <w:bCs/>
                                    <w:kern w:val="44"/>
                                    <w:sz w:val="44"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                            </w:style>
                            <w:style w:type="character" w:default="1" w:styleId="a0">
                                <w:name w:val="Default Paragraph Font"/>
                                <w:uiPriority w:val="1"/>
                                <w:semiHidden/>
                                <w:unhideWhenUsed/>
                            </w:style>
                            <w:style w:type="table" w:default="1" w:styleId="a1">
                                <w:name w:val="Normal Table"/>
                                <w:uiPriority w:val="99"/>
                                <w:semiHidden/>
                                <w:unhideWhenUsed/>
                                <w:tblPr>
                                    <w:tblInd w:w="0" w:type="dxa"/>
                                    <w:tblCellMar>
                                        <w:top w:w="0" w:type="dxa"/>
                                        <w:left w:w="108" w:type="dxa"/>
                                        <w:bottom w:w="0" w:type="dxa"/>
                                        <w:right w:w="108" w:type="dxa"/>
                                    </w:tblCellMar>
                                </w:tblPr>
                            </w:style>
                            <w:style w:type="numbering" w:default="1" w:styleId="a2">
                                <w:name w:val="No List"/>
                                <w:uiPriority w:val="99"/>
                                <w:semiHidden/>
                                <w:unhideWhenUsed/>
                            </w:style>
                            <w:style w:type="paragraph" w:styleId="a3">
                                <w:name w:val="header"/>
                                <w:basedOn w:val="a"/>
                                <w:link w:val="a4"/>
                                <w:uiPriority w:val="99"/>
                                <w:unhideWhenUsed/>
                                <w:rsid w:val="00170924"/>
                                <w:pPr>
                                    <w:tabs>
                                        <w:tab w:val="center" w:pos="4153"/>
                                        <w:tab w:val="right" w:pos="8306"/>
                                    </w:tabs>
                                    <w:snapToGrid w:val="0"/>
                                    <w:jc w:val="center"/>
                                </w:pPr>
                                <w:rPr>
                                    <w:sz w:val="18"/>
                                    <w:szCs w:val="18"/>
                                </w:rPr>
                            </w:style>
                            <w:style w:type="character" w:customStyle="1" w:styleId="a4">
                                <w:name w:val="页眉 字符"/>
                                <w:basedOn w:val="a0"/>
                                <w:link w:val="a3"/>
                                <w:uiPriority w:val="99"/>
                                <w:rsid w:val="00170924"/>
                                <w:rPr>
                                    <w:sz w:val="18"/>
                                    <w:szCs w:val="18"/>
                                </w:rPr>
                            </w:style>
                            <w:style w:type="character" w:customStyle="1" w:styleId="10">
                                <w:name w:val="标题 1 字符"/>
                                <w:basedOn w:val="a0"/>
                                <w:link w:val="1"/>
                                <w:uiPriority w:val="9"/>
                                <w:rsid w:val="0026451C"/>
                                <w:rPr>
                                    <w:b/>
                                    <w:bCs/>
                                    <w:kern w:val="44"/>
                                    <w:sz w:val="44"/>
                                    <w:szCs w:val="44"/>
                                </w:rPr>
                            </w:style>
                            <w:style w:type="paragraph" w:styleId="TOC">
                                <w:name w:val="TOC Heading"/>
                                <w:basedOn w:val="1"/>
                                <w:next w:val="a"/>
                                <w:uiPriority w:val="39"/>
                                <w:unhideWhenUsed/>
                                <w:qFormat/>
                                <w:rsid w:val="0026451C"/>
                                <w:pPr>
                                    <w:widowControl/>
                                    <w:spacing w:before="240" w:after="0" w:line="259" w:lineRule="auto"/>
                                    <w:jc w:val="left"/>
                                    <w:outlineLvl w:val="9"/>
                                </w:pPr>
                                <w:rPr>
                                    <w:rFonts w:asciiTheme="majorHAnsi" w:eastAsiaTheme="majorEastAsia" w:hAnsiTheme="majorHAnsi" w:cstheme="majorBidi"/>
                                    <w:b w:val="0"/>
                                    <w:bCs w:val="0"/>
                                    <w:color w:val="2F5496" w:themeColor="accent1" w:themeShade="BF"/>
                                    <w:kern w:val="0"/>
                                    <w:sz w:val="32"/>
                                    <w:szCs w:val="32"/>
                                </w:rPr>
                            </w:style>
                            <w:style w:type="paragraph" w:styleId="a5">
                                <w:name w:val="List Paragraph"/>
                                <w:basedOn w:val="a"/>
                                <w:uiPriority w:val="34"/>
                                <w:qFormat/>
                                <w:rsid w:val="00C95671"/>
                                <w:pPr>
                                    <w:ind w:firstLineChars="200" w:firstLine="420"/>
                                </w:pPr>
                            </w:style>
                            <w:style w:type="paragraph" w:styleId="a6">
                                <w:name w:val="footer"/>
                                <w:basedOn w:val="a"/>
                                <w:link w:val="a7"/>
                                <w:uiPriority w:val="99"/>
                                <w:unhideWhenUsed/>
                                <w:rsid w:val="006A7F94"/>
                                <w:pPr>
                                    <w:tabs>
                                        <w:tab w:val="center" w:pos="4153"/>
                                        <w:tab w:val="right" w:pos="8306"/>
                                    </w:tabs>
                                    <w:snapToGrid w:val="0"/>
                                    <w:jc w:val="left"/>
                                </w:pPr>
                                <w:rPr>
                                    <w:sz w:val="18"/>
                                    <w:szCs w:val="18"/>
                                </w:rPr>
                            </w:style>
                            <w:style w:type="character" w:customStyle="1" w:styleId="a7">
                                <w:name w:val="页脚 字符"/>
                                <w:basedOn w:val="a0"/>
                                <w:link w:val="a6"/>
                                <w:uiPriority w:val="99"/>
                                <w:rsid w:val="006A7F94"/>
                                <w:rPr>
                                    <w:sz w:val="18"/>
                                    <w:szCs w:val="18"/>
                                </w:rPr>
                            </w:style>
                            <w:style w:type="paragraph" w:styleId="TOC1">
                                <w:name w:val="toc 1"/>
                                <w:basedOn w:val="a"/>
                                <w:next w:val="a"/>
                                <w:link w:val="TOC10"/>
                                <w:autoRedefine/>
                                <w:uiPriority w:val="39"/>
                                <w:unhideWhenUsed/>
                                <w:rsid w:val="00532994"/>
                            </w:style>
                            <w:style w:type="character" w:styleId="a8">
                                <w:name w:val="Hyperlink"/>
                                <w:basedOn w:val="a0"/>
                                <w:uiPriority w:val="99"/>
                                <w:unhideWhenUsed/>
                                <w:rsid w:val="00532994"/>
                                <w:rPr>
                                    <w:color w:val="0563C1" w:themeColor="hyperlink"/>
                                    <w:u w:val="single"/>
                                </w:rPr>
                            </w:style>
                            <w:style w:type="paragraph" w:customStyle="1" w:styleId="a9">
                                <w:name w:val="目录样式"/>
                                <w:basedOn w:val="TOC1"/>
                                <w:link w:val="aa"/>
                                <w:autoRedefine/>
                                <w:qFormat/>
                                <w:rsid w:val="00532994"/>
                                <w:pPr>
                                    <w:tabs>
                                        <w:tab w:val="right" w:leader="dot" w:pos="8296"/>
                                    </w:tabs>
                                    <w:spacing w:line="312" w:lineRule="auto"/>
                                </w:pPr>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:noProof/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:style>
                            <w:style w:type="character" w:customStyle="1" w:styleId="TOC10">
                                <w:name w:val="TOC 1 字符"/>
                                <w:basedOn w:val="a0"/>
                                <w:link w:val="TOC1"/>
                                <w:uiPriority w:val="39"/>
                                <w:rsid w:val="00532994"/>
                            </w:style>
                            <w:style w:type="character" w:customStyle="1" w:styleId="aa">
                                <w:name w:val="目录样式 字符"/>
                                <w:basedOn w:val="TOC10"/>
                                <w:link w:val="a9"/>
                                <w:rsid w:val="00532994"/>
                                <w:rPr>
                                    <w:rFonts w:ascii="黑体" w:eastAsia="黑体" w:hAnsi="黑体"/>
                                    <w:noProof/>
                                    <w:sz w:val="32"/>
                                </w:rPr>
                            </w:style>
                        </w:styles>
                    </pkg:xmlData>
                </pkg:part>
                <pkg:part pkg:name="/word/webSettings.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.webSettings+xml">
                    <pkg:xmlData>
                        <w:webSettings
                            xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                            xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                            xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                            xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                            xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                            xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                            xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                            xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                            xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh">
                            <w:optimizeForBrowser/>
                            <w:allowPNG/>
                        </w:webSettings>
                    </pkg:xmlData>
                </pkg:part>
                <pkg:part pkg:name="/word/fontTable.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.wordprocessingml.fontTable+xml">
                    <pkg:xmlData>
                        <w:fonts
                            xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
                            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                            xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                            xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
                            xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
                            xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
                            xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
                            xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
                            xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
                            xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex" mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh">
                            <w:font w:name="等线">
                                <w:altName w:val="DengXian"/>
                                <w:panose1 w:val="02010600030101010101"/>
                                <w:charset w:val="86"/>
                                <w:family w:val="auto"/>
                                <w:pitch w:val="variable"/>
                                <w:sig w:usb0="A00002BF" w:usb1="38CF7CFA" w:usb2="00000016" w:usb3="00000000" w:csb0="0004000F" w:csb1="00000000"/>
                            </w:font>
                            <w:font w:name="Times New Roman">
                                <w:panose1 w:val="02020603050405020304"/>
                                <w:charset w:val="00"/>
                                <w:family w:val="roman"/>
                                <w:pitch w:val="variable"/>
                                <w:sig w:usb0="E0002EFF" w:usb1="C000785B" w:usb2="00000009" w:usb3="00000000" w:csb0="000001FF" w:csb1="00000000"/>
                            </w:font>
                            <w:font w:name="等线 Light">
                                <w:panose1 w:val="02010600030101010101"/>
                                <w:charset w:val="86"/>
                                <w:family w:val="auto"/>
                                <w:pitch w:val="variable"/>
                                <w:sig w:usb0="A00002BF" w:usb1="38CF7CFA" w:usb2="00000016" w:usb3="00000000" w:csb0="0004000F" w:csb1="00000000"/>
                            </w:font>
                            <w:font w:name="黑体">
                                <w:altName w:val="SimHei"/>
                                <w:panose1 w:val="02010609060101010101"/>
                                <w:charset w:val="86"/>
                                <w:family w:val="modern"/>
                                <w:pitch w:val="fixed"/>
                                <w:sig w:usb0="800002BF" w:usb1="38CF7CFA" w:usb2="00000016" w:usb3="00000000" w:csb0="00040001" w:csb1="00000000"/>
                            </w:font>
                            <w:font w:name="方正小标宋简体">
                                <w:panose1 w:val="02010601030101010101"/>
                                <w:charset w:val="86"/>
                                <w:family w:val="auto"/>
                                <w:pitch w:val="variable"/>
                                <w:sig w:usb0="00000001" w:usb1="080E0000" w:usb2="00000010" w:usb3="00000000" w:csb0="00040000" w:csb1="00000000"/>
                            </w:font>
                            <w:font w:name="宋体">
                                <w:altName w:val="SimSun"/>
                                <w:panose1 w:val="02010600030101010101"/>
                                <w:charset w:val="86"/>
                                <w:family w:val="auto"/>
                                <w:pitch w:val="variable"/>
                                <w:sig w:usb0="00000003" w:usb1="288F0000" w:usb2="00000016" w:usb3="00000000" w:csb0="00040001" w:csb1="00000000"/>
                            </w:font>
                            <w:font w:name="仿宋">
                                <w:panose1 w:val="02010609060101010101"/>
                                <w:charset w:val="86"/>
                                <w:family w:val="modern"/>
                                <w:pitch w:val="fixed"/>
                                <w:sig w:usb0="800002BF" w:usb1="38CF7CFA" w:usb2="00000016" w:usb3="00000000" w:csb0="00040001" w:csb1="00000000"/>
                            </w:font>
                        </w:fonts>
                    </pkg:xmlData>
                </pkg:part>
                <pkg:part pkg:name="/docProps/core.xml" pkg:contentType="application/vnd.openxmlformats-package.core-properties+xml" pkg:padding="256">
                    <pkg:xmlData>
                        <cp:coreProperties
                            xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"
                            xmlns:dc="http://purl.org/dc/elements/1.1/"
                            xmlns:dcterms="http://purl.org/dc/terms/"
                            xmlns:dcmitype="http://purl.org/dc/dcmitype/"
                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                            <dc:title/>
                            <dc:subject/>
                            <dc:creator>慕若曦</dc:creator>
                            <cp:keywords/>
                            <dc:description/>
                            <cp:lastModifiedBy>慕若曦</cp:lastModifiedBy>
                            <cp:revision>2</cp:revision>
                            <dcterms:created xsi:type="dcterms:W3CDTF">2022-07-30T10:37:00Z</dcterms:created>
                            <dcterms:modified xsi:type="dcterms:W3CDTF">2022-07-30T10:37:00Z</dcterms:modified>
                        </cp:coreProperties>
                    </pkg:xmlData>
                </pkg:part>
                <pkg:part pkg:name="/docProps/app.xml" pkg:contentType="application/vnd.openxmlformats-officedocument.extended-properties+xml" pkg:padding="256">
                    <pkg:xmlData>
                        <Properties
                            xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"
                            xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
                            <Template>Normal.dotm</Template>
                            <TotalTime>0</TotalTime>
                            <Pages>4</Pages>
                            <Words>208</Words>
                            <Characters>1190</Characters>
                            <Application>Microsoft Office Word</Application>
                            <DocSecurity>0</DocSecurity>
                            <Lines>9</Lines>
                            <Paragraphs>2</Paragraphs>
                            <ScaleCrop>false</ScaleCrop>
                            <Company/>
                            <LinksUpToDate>false</LinksUpToDate>
                            <CharactersWithSpaces>1396</CharactersWithSpaces>
                            <SharedDoc>false</SharedDoc>
                            <HyperlinksChanged>false</HyperlinksChanged>
                            <AppVersion>16.0000</AppVersion>
                        </Properties>
                    </pkg:xmlData>
                </pkg:part>
                <pkg:part pkg:name="/customXml/_rels/item1.xml.rels" pkg:contentType="application/vnd.openxmlformats-package.relationships+xml" pkg:padding="256">
                    <pkg:xmlData>
                        <Relationships
                            xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
                            <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/customXmlProps" Target="itemProps1.xml"/>
                        </Relationships>
                    </pkg:xmlData>
                </pkg:part>
            </pkg:package>';
        }
