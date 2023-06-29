#include "stdafx.h"

#include "DxInputDevice.h"
#include "editmeshs.h"
#include "DxMethods.h"
#include "DxViewPort.h"
#include "DxEffectMan.h"
#include "DxShadowMap.h"
#include "EditMeshs.h"
#include "GLogicData.h"
#include "GLItemMan.h"
#include "DxGlobalStage.h"
#include "GLGaeaClient.h"
#include "stl_Func.h"
#include "DxEffGroupPlayer.h"
#include "GLPartyClient.h"
#include "GLBusStation.h"
#include "GLBusData.h"
#include "GLTaxiStation.h"
#include "RANPARAM.h"
#include "GLMaplist.h"
#include "GLFriendClient.h"
#include "RanFilter.h"
#include "DxClubMan.h"
#include "GLFactEffect.h"
#include "GLQUEST.h"
#include "GLQUESTMAN.h"
#include "GLCharacter.h"
#include "GLItemMixMan.h"
#include "GLItem.h"

#include "../RanClientUILib/Interface/UITextControl.h"
#include "../RanClientUILib/Interface/GameTextControl.h"
#include "../RanClientUILib/Interface/InnerInterface.h"
#include "../RanClientUILib/Interface/BasicGameMenu.h"
#include "../RanClientUILib/Interface/QBoxButton.h"
#include "../RanClientUILib/Interface/ModalWindow.h"
#include "../RanClientUILib/Interface/ModalCallerID.h"
#include "../RanClientUILib/Interface/ItemShopIconMan.h"
#include "../RanClientUILib/Interface/PetskinMixImage.h"

#include "../enginelib/Common/StringUtils.h"

//#include "./ObserverNotifyID.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

void GLCharacter::ReqToggleRun ()
{
	if ( IsSTATE(EM_ACT_RUN) )
	{
		ReSetSTATE(EM_ACT_RUN);
		CBasicGameMenu * pGameMenu = CInnerInterface::GetInstance().GetGameMenu();
		if( pGameMenu ) pGameMenu->SetFlipRunButton( FALSE );
	}
	else
	{
		SetSTATE(EM_ACT_RUN);
		CBasicGameMenu * pGameMenu = CInnerInterface::GetInstance().GetGameMenu();
		if( pGameMenu ) pGameMenu->SetFlipRunButton( TRUE );
	}

	m_actorMove.SetMaxSpeed ( GetMoveVelo() );

	PGLPETCLIENT pMyPet = GLGaeaClient::GetInstance().GetPetClient ();
	if ( pMyPet->IsVALID () )
	{
		pMyPet->SetMoveState ( IsSTATE(EM_ACT_RUN) );
	}

	//	Note : ¸Þ¼¼Áö ¹ß»ý.
	//
	GLMSG::SNETPC_MOVESTATE NetMsg;
	NetMsg.dwActState = m_dwActState;

	NETSENDTOFIELD ( (NET_MSG_GENERIC*) &NetMsg );
}

void GLCharacter::ReqTogglePeaceMode ()
{
	if ( IsSTATE(EM_ACT_PEACEMODE) && !m_bVehicle )	ReSetSTATE(EM_ACT_PEACEMODE);
	else											SetSTATE(EM_ACT_PEACEMODE);

	m_fIdleTime = 0.0f;

	//	Note : ¸Þ¼¼Áö ¹ß»ý.
	//
	GLMSG::SNETPC_ACTSTATE NetMsg;
	NetMsg.dwActState = m_dwActState;
	NETSEND ( (NET_MSG_GENERIC*) &NetMsg );
}

/*void GLCharacter::ReqToggleBooster () //add bike booster
{
	if ( IsSTATE(EM_ACT_BOOSTER) /*&& m_bVehicle )	ReSetSTATE(EM_ACT_BOOSTER);
	else  if ( m_bVehicle )	SetSTATE( EM_ACT_BOOSTER );

	GLMSG::SNETPC_ACTSTATE NetMsg;
	NetMsg.dwActState = m_dwActState;
	NETSEND ( (NET_MSG_GENERIC*) &NetMsg );
}
*/
void GLCharacter::ReqVisibleNone ()
{
	SetSTATE(EM_REQ_VISIBLENONE);

	//	Note : (¿¡ÀÌÁ¯Æ®¼­¹ö) ¸Þ¼¼Áö ¹ß»ý.
	//
	GLMSG::SNETPC_ACTSTATE NetMsg;
	NetMsg.dwActState = m_dwActState;
	NETSEND ( (NET_MSG_GENERIC*) &NetMsg );
}

void GLCharacter::ReqVisibleOff ()
{
	SetSTATE(EM_REQ_VISIBLEOFF);

	//	Note : (¿¡ÀÌÁ¯Æ®¼­¹ö) ¸Þ¼¼Áö ¹ß»ý.
	//
	GLMSG::SNETPC_ACTSTATE NetMsg;
	NetMsg.dwActState = m_dwActState;
	NETSEND ( (NET_MSG_GENERIC*) &NetMsg );
}

void GLCharacter::ReqVisibleOn ()
{
	ReSetSTATE(EM_REQ_VISIBLENONE);
	ReSetSTATE(EM_REQ_VISIBLEOFF);

	//	Note : (¿¡ÀÌÁ¯Æ®¼­¹ö) ¸Þ¼¼Áö ¹ß»ý.
	//
	GLMSG::SNETPC_ACTSTATE NetMsg;
	NetMsg.dwActState = m_dwActState;
	NETSEND ( (NET_MSG_GENERIC*) &NetMsg );
}

// *****************************************************
// Desc: Ãâ±¸ ³ª°¡±â ¿äÃ»
// *****************************************************
void GLCharacter::ReqGateOut ()
{

	if ( IsSTATE(EM_REQ_GATEOUT) )							return;
	if ( IsSTATE(EM_ACT_WAITING) )							return;

	DWORD dwGateID = DetectGate ();
	if ( dwGateID==UINT_MAX )								return;

	PLANDMANCLIENT pLandMClient = GLGaeaClient::GetInstance().GetActiveMap();
	if ( !pLandMClient )								return;

	DxLandGateMan *pLandGateMan = &pLandMClient->GetLandGateMan();
	if ( !pLandGateMan )								return;

	PDXLANDGATE pLandGate = pLandGateMan->FindLandGate ( dwGateID );
	if ( !pLandGate )									return;

	SNATIVEID sMapID = pLandGate->GetToMapID();

	SMAPNODE *pMapNode = GLGaeaClient::GetInstance().FindMapNode ( sMapID );
	if ( !pMapNode )									return;


	//	TODO :  +, quest, ÆÄÆ¼¿ø Ã¼Å©´Â ÇâÈÄ ±¸Çö, Å¬¶óÀÌ¾ðÆ®¿¡¼­¸¸ Ã³¸®ÇÏ°í ÀÖÀ½. ¼­¹ö¿¡¼­ Á¡°ËÀº ³ªÁß¿¡.
	//
	//	TODO : Ãâ±¸ »ç¿ë Á¶°ÇÀ» °Ë»çÇÏ¿©¾ß ÇÏÁö¸¸ ÇöÀç ¿¡ÀÌÁ¯Æ® ¼­¹ö¿¡¼­ È®ÀÎÇÒ ¼ö ÀÖ´Â
	//		ÄÉ¸¯ÅÍ Á¤º¸ÀÇ ÇÑ°è°¡ ÀÖÀ¸¹Ç·Î Å¬¶óÀÌ¾ðÆ®¿¡¼­¸¸ È®ÀÎµÇ°í ÀÖÀ½.
	//

	EMREQFAIL emReqFail(EMREQUIRE_COMPLETE);
	
	GLLevelFile cLevelFile;
	BOOL bOk = cLevelFile.LoadFile ( pMapNode->strFile.c_str(), TRUE, NULL );
	if ( !bOk )											return;

	 if ( GLGaeaClient::GetInstance().GetPetClient()->IsVALID() ) 
		{
			CInnerInterface &cINTERFACE1 = CInnerInterface::GetInstance();
			cINTERFACE1.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, "Please disable your pet first.");
			return;
		}

	SLEVEL_REQUIRE* pRequire = cLevelFile.GetLevelRequire ();
	emReqFail = pRequire->ISCOMPLETE ( this );
	if ( emReqFail != EMREQUIRE_COMPLETE )
	{
		CInnerInterface &cINTERFACE = CInnerInterface::GetInstance();
		switch ( emReqFail )
		{
		case EMREQUIRE_LEVEL:
			{
				if( pRequire->m_signLevel == EMSIGN_FROMTO )
				{
					cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
						ID2GAMEINTEXT("EMREQUIRE_LEVEL2"),
						pRequire->m_wLevel,
						pRequire->m_wLevel2 );
				}else{
					std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signLevel].c_str());
					
					if( RANPARAM::emSERVICE_TYPE == EMSERVICE_THAILAND )
					{
						cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
													ID2GAMEINTEXT("EMREQUIRE_LEVEL"),
													strSIGN.c_str(),
													pRequire->m_wLevel );
					}
					else
					{
						cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
													ID2GAMEINTEXT("EMREQUIRE_LEVEL"),
													pRequire->m_wLevel,
													strSIGN.c_str() );
					}
				}
			}
			break;

		case EMREQUIRE_ITEM:
			{
				SITEM *pItem = GLItemMan::GetInstance().GetItem ( pRequire->m_sItemID );
				if ( pItem )
				{
					cINTERFACE.PrintMsgTextDlg
					(
						NS_UITEXTCOLOR::DISABLE,
						ID2GAMEINTEXT("EMREQUIRE_ITEM"),
						pItem->GetName()
					);
				}
			}
			break;

		case EMREQUIRE_SKILL:
			{
				PGLSKILL pSkill = GLSkillMan::GetInstance().GetData ( pRequire->m_sSkillID );
				if ( pSkill )
				{
					cINTERFACE.PrintMsgTextDlg
					(
						NS_UITEXTCOLOR::DISABLE,
						ID2GAMEINTEXT("EMREQUIRE_SKILL"),
						pSkill->GetName()
					);
				}
			}
			break;

		case EMREQUIRE_LIVING:
			{
				std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signLiving].c_str());
				cINTERFACE.PrintMsgTextDlg
				(
					NS_UITEXTCOLOR::DISABLE,
					ID2GAMEINTEXT("EMREQUIRE_LIVING"),
					pRequire->m_nLiving,
					strSIGN.c_str()
				);
			}
			break;

		case EMREQUIRE_BRIGHT:
			{
				std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signBright].c_str());
				cINTERFACE.PrintMsgTextDlg
				(
					NS_UITEXTCOLOR::DISABLE,
					ID2GAMEINTEXT("EMREQUIRE_BRIGHT"),
					pRequire->m_nBright,
					strSIGN.c_str()
				);
			}
			break;

		case EMREQUIRE_QUEST_COM:
			{
				CString strQUEST = "quest";
				GLQUEST *pQUEST = GLQuestMan::GetInstance().Find ( pRequire->m_sComQuestID.dwID );
				if ( pQUEST )		strQUEST = pQUEST->GetTITLE();

				cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQUIRE_QUEST_COM"), strQUEST.GetString() );
			}
			break;

		case EMREQUIRE_QUEST_ACT:
			{
				CString strQUEST = "quest";
				GLQUEST *pQUEST = GLQuestMan::GetInstance().Find ( pRequire->m_sActQuestID.dwID );
				if ( pQUEST )		strQUEST = pQUEST->GetTITLE();

				cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQUIRE_QUEST_ACT"), strQUEST.GetString() );
			}
			break;

		default:
			cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("MAP_REQUIRE_FAIL") );
			break;
		};

		//	Note : Ãâ±¸ »ç¿ë ±ÇÇÑÀÌ ¾ÈµÉ °æ¿ì. GM level ÀÌ»óÀÏ °æ¿ì Á¶°Ç ¹«½Ã.
		//
		if ( m_dwUserLvl < USER_GM3 )	return;
	}


	if( pMapNode->bInstantMap )
	{
		//	Note : ÀÎ½ºÅÏ½º ¸Ê »ý¼º ¿äÃ»
		//
		GLMSG::SNETREQ_CREATE_INSTANT_MAP_REQ NetMsg;
		NetMsg.dwGateID = dwGateID;
		NETSENDTOFIELD ( &NetMsg );
		SetSTATE(EM_REQ_GATEOUT);
		return;
	}
	DISABLEALLLANDEFF();
	//	Note : Ãâ±¸ ³ª°¡±â ¿äÃ» ¸Þ½ÃÁö ¹ß»ý.
	//
	GLMSG::SNETREQ_GATEOUT_REQ NetMsg;
	NetMsg.dwGateID = dwGateID;
	NETSENDTOFIELD ( &NetMsg );

	SetSTATE(EM_REQ_GATEOUT);

}
 //gate move without rangeportal - eduj this is just a lilmodif
void GLCharacter::ReqGateOutMove ( int i, SNATIVEID sMAPID )
{
	if ( IsSTATE(EM_REQ_GATEOUT) )							return;
	if ( IsSTATE(EM_ACT_WAITING) )							return;

	DWORD dwGateID = i;
	if ( dwGateID==UINT_MAX )								return;

	PLANDMANCLIENT pLandMClient = GLGaeaClient::GetInstance().GetActiveMap();
	if ( !pLandMClient )								return;

	DxLandGateMan *pLandGateMan = &pLandMClient->GetLandGateMan();
	if ( !pLandGateMan )								return;

	PDXLANDGATE pLandGate = pLandGateMan->FindLandGate ( dwGateID );
	if ( !pLandGate )									return;


	SMAPNODE *pMapNode = GLGaeaClient::GetInstance().FindMapNode ( sMAPID );
	if ( !pMapNode )									return;					


	//	TODO :  +, quest, ÆÄÆ¼¿ø Ã¼Å©´Â ÇâÈÄ ±¸Çö, Å¬¶óÀÌ¾ðÆ®¿¡¼­¸¸ Ã³¸®ÇÏ°í ÀÖÀ½. ¼­¹ö¿¡¼­ Á¡°ËÀº ³ªÁß¿¡.
	//
	//	TODO : Ãâ±¸ »ç¿ë Á¶°ÇÀ» °Ë»çÇÏ¿©¾ß ÇÏÁö¸¸ ÇöÀç ¿¡ÀÌÁ¯Æ® ¼­¹ö¿¡¼­ È®ÀÎÇÒ ¼ö ÀÖ´Â
	//		ÄÉ¸¯ÅÍ Á¤º¸ÀÇ ÇÑ°è°¡ ÀÖÀ¸¹Ç·Î Å¬¶óÀÌ¾ðÆ®¿¡¼­¸¸ È®ÀÎµÇ°í ÀÖÀ½.
	//

	EMREQFAIL emReqFail(EMREQUIRE_COMPLETE);
	
	GLLevelFile cLevelFile;
	BOOL bOk = cLevelFile.LoadFile ( pMapNode->strFile.c_str(), TRUE, NULL );
	if ( !bOk )											return;

	SLEVEL_REQUIRE* pRequire = cLevelFile.GetLevelRequire ();
	emReqFail = pRequire->ISCOMPLETE ( this );
	if ( emReqFail != EMREQUIRE_COMPLETE )
	{
		CInnerInterface &cINTERFACE = CInnerInterface::GetInstance();
		switch ( emReqFail )
		{
		case EMREQUIRE_LEVEL:
			{
				if( pRequire->m_signLevel == EMSIGN_FROMTO )
				{
					cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
						ID2GAMEINTEXT("EMREQUIRE_LEVEL2"),
						pRequire->m_wLevel,
						pRequire->m_wLevel2 );
				}else{
					std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signLevel].c_str());
					
					if( RANPARAM::emSERVICE_TYPE == EMSERVICE_THAILAND )
					{
						cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
													ID2GAMEINTEXT("EMREQUIRE_LEVEL"),
													strSIGN.c_str(),
													pRequire->m_wLevel );
					}
					else
					{
						cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
													ID2GAMEINTEXT("EMREQUIRE_LEVEL"),
													pRequire->m_wLevel,
													strSIGN.c_str() );
					}
				}
			}
			break;

		case EMREQUIRE_ITEM:
			{
				SITEM *pItem = GLItemMan::GetInstance().GetItem ( pRequire->m_sItemID );
				if ( pItem )
				{
					cINTERFACE.PrintMsgTextDlg
					(
						NS_UITEXTCOLOR::DISABLE,
						ID2GAMEINTEXT("EMREQUIRE_ITEM"),
						pItem->GetName()
					);
				}
			}
			break;

		case EMREQUIRE_SKILL:
			{
				PGLSKILL pSkill = GLSkillMan::GetInstance().GetData ( pRequire->m_sSkillID );
				if ( pSkill )
				{
					cINTERFACE.PrintMsgTextDlg
					(
						NS_UITEXTCOLOR::DISABLE,
						ID2GAMEINTEXT("EMREQUIRE_SKILL"),
						pSkill->GetName()
					);
				}
			}
			break;

		case EMREQUIRE_LIVING:
			{
				std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signLiving].c_str());
				cINTERFACE.PrintMsgTextDlg
				(
					NS_UITEXTCOLOR::DISABLE,
					ID2GAMEINTEXT("EMREQUIRE_LIVING"),
					pRequire->m_nLiving,
					strSIGN.c_str()
				);
			}
			break;

		case EMREQUIRE_BRIGHT:
			{
				std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signBright].c_str());
				cINTERFACE.PrintMsgTextDlg
				(
					NS_UITEXTCOLOR::DISABLE,
					ID2GAMEINTEXT("EMREQUIRE_BRIGHT"),
					pRequire->m_nBright,
					strSIGN.c_str()
				);
			}
			break;

		case EMREQUIRE_QUEST_COM:
			{
				CString strQUEST = "quest";
				GLQUEST *pQUEST = GLQuestMan::GetInstance().Find ( pRequire->m_sComQuestID.dwID );
				if ( pQUEST )		strQUEST = pQUEST->GetTITLE();

				cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQUIRE_QUEST_COM"), strQUEST.GetString() );
			}
			break;

		case EMREQUIRE_QUEST_ACT:
			{
				CString strQUEST = "quest";
				GLQUEST *pQUEST = GLQuestMan::GetInstance().Find ( pRequire->m_sActQuestID.dwID );
				if ( pQUEST )		strQUEST = pQUEST->GetTITLE();

				cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQUIRE_QUEST_ACT"), strQUEST.GetString() );
			}
			break;

		default:
			cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("MAP_REQUIRE_FAIL") );
			break;
		};

		//	Note : Ãâ±¸ »ç¿ë ±ÇÇÑÀÌ ¾ÈµÉ °æ¿ì. GM level ÀÌ»óÀÏ °æ¿ì Á¶°Ç ¹«½Ã.
		//
		if ( m_dwUserLvl < USER_GM3 )	return;
	}


	if( pMapNode->bInstantMap )
	{
		//	Note : ÀÎ½ºÅÏ½º ¸Ê »ý¼º ¿äÃ»
		//
		GLMSG::SNETREQ_CREATE_INSTANT_MAP_REQ NetMsg;
		NetMsg.dwGateID = dwGateID;
		NETSENDTOFIELD ( &NetMsg );
		SetSTATE(EM_REQ_GATEOUT);
		return;
	}
	DISABLEALLLANDEFF();

	if ( m_bSafeZone ) m_bSafeZone = false;

	//	Note : Ãâ±¸ ³ª°¡±â ¿äÃ» ¸Þ½ÃÁö ¹ß»ý.
	//
	GLMSG::SNETREQ_GATEOUT_REQ NetMsg;
	NetMsg.dwGateID = dwGateID;
	NETSENDTOFIELD ( &NetMsg );

	SetSTATE(EM_REQ_GATEOUT);

}

bool GLCharacter::ReqGESTURE ( int nMOTION, bool bCOMMAND )
{
	//	Note : ¸ð¼Ç Á¾·ù°¡ ÆòÈ­ ¸ðµåÀÏ °æ¿ì¸¸ Á¦½ºÃÄ¸¦ ÇÒ ¼ö ÀÖ´Ù.
	PLANDMANCLIENT pLand = GLGaeaClient::GetInstance().GetActiveMap();
	BOOL bPeaceZone = pLand ? pLand->IsPeaceZone() : FALSE;

	//	Note : ¸í·É¾î·Î ±¸µ¿µÇ´Â Á¦½ºÃÄÀÏ °æ¿ì. ÆòÈ­¸ðµå·Î Á¯È¯.
	if ( bCOMMAND )
	{
		if ( !bPeaceZone && !IsSTATE(EM_ACT_PEACEMODE) )
		{
			if ( IsACTION(GLAT_IDLE) )		ReqTogglePeaceMode();
		}
	}

	if ( !bPeaceZone && IsSTATE(EM_ACT_PEACEMODE) )
	{
		bPeaceZone = TRUE;
	}

	if ( !bPeaceZone )	return false;

	// Å»°Í Å¾½ÂÁßÀÏ¶§ Á¦½ºÃÄ ±ÝÁö
	if ( m_bVehicle ) return true;

	//	Note : ÇØ´ç Á¦½ºÃÄ ¿¡´Ï¸ÞÀÌ¼ÇÀÌ Á¸Á¦½Ã ±¸µ¿.
	PANIMCONTNODE pNODE = m_pSkinChar->GETANI ( AN_GESTURE, EMANI_SUBTYPE(nMOTION) );
	if ( !pNODE )			return false;

	//	Note : ½ÅÃ¼°¡ Á¤»óÀûÀÏ¶§ ±¸µ¿.
	if ( !IsValidBody() )	return false;

	//	Note : Á¦½ºÃÄ¸¦ ÇàÇÔ.
	m_dwANISUBGESTURE = (DWORD) nMOTION;
	TurnAction(GLAT_TALK);

	//	Note : ¼­¹ö·Î ¸Þ½ÃÁö Àü¼Û.
	GLMSG::SNETPC_REQ_GESTURE NetMsg;
	NetMsg.dwID = m_dwANISUBGESTURE;
	NETSENDTOFIELD ( &NetMsg );

	return true;
}

inline bool GLCharacter::IsInsertToInven ( PITEMCLIENTDROP pItemDrop )
{
	GASSERT(pItemDrop&&"GLChar::IsItemToInven()");
	if ( !pItemDrop )	return false;

	SITEM *pItem = GLItemMan::GetInstance().GetItem(pItemDrop->sItemClient.sNativeID);
	if ( !pItem )		return false;

	if ( pItem->ISPILE() )
	{
		WORD wINVENX = pItem->sBasicOp.wInvenSizeX;
		WORD wINVENY = pItem->sBasicOp.wInvenSizeY;

		//	°ãÄ§ ¾ÆÀÌÅÛÀÏ °æ¿ì.
		WORD wPILENUM = pItem->sDrugOp.wPileNum;
		SNATIVEID sNID = pItem->sBasicOp.sNativeID;

		//	Note : ³Ö±â ¿äÃ»µÈ ¾ÆÀÌÅÛ¼ö. ( ÀÜ¿©·®. )
		//
		WORD wREQINSRTNUM = ( pItemDrop->sItemClient.wTurnNum );

		BOOL bITEM_SPACE = TRUE;
#if defined(VN_PARAM) //vietnamtest%%%
		if( m_dwVietnamGainType == GAINTYPE_EMPTY )
		{
			bITEM_SPACE = m_cVietnamInventory.ValidPileInsrt ( wREQINSRTNUM, sNID, wPILENUM, wINVENX, wINVENY );
		}else{
			bITEM_SPACE = m_cInventory.ValidPileInsrt ( wREQINSRTNUM, sNID, wPILENUM, wINVENX, wINVENY );
		}		
#else
		bITEM_SPACE = m_cInventory.ValidPileInsrt ( wREQINSRTNUM, sNID, wPILENUM, wINVENX, wINVENY );
#endif
		if ( !bITEM_SPACE )		return false;
	}
	else
	{
		WORD wPosX, wPosY;
		BOOL bOk = TRUE;
#if defined(VN_PARAM) //vietnamtest%%%
		if( m_dwVietnamGainType == GAINTYPE_EMPTY )
		{
			bOk = m_cVietnamInventory.FindInsrtable ( pItem->sBasicOp.wInvenSizeX, pItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
		}else{
			bOk = m_cInventory.FindInsrtable ( pItem->sBasicOp.wInvenSizeX, pItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
		}
#else
		bOk = m_cInventory.FindInsrtable ( pItem->sBasicOp.wInvenSizeX, pItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
#endif
		if ( !bOk )				return false;
	}

	return true;
}

//	Note : ÇÊµå ¾ÆÀÌÅÛ(µ·) ÁÖÀ»¶§.
HRESULT GLCharacter::ReqFieldTo ( const STARGETID &sTargetID, bool bPet )
{
	if ( VALID_HOLD_ITEM () )					return E_FAIL;
	if ( ValidWindowOpen() )					return E_FAIL;	

	PLANDMANCLIENT pLAND = GLGaeaClient::GetInstance().GetActiveMap();
	if ( !pLAND )	return E_FAIL;

	//if ( sTargetID.emCrow==CROW_ITEM )
	//{
	//	if ( pLAND->ISITEM_PICKDELAY ( sTargetID.dwID ) )	return E_FAIL;
	//}
	//else if ( sTargetID.emCrow==CROW_MONEY )
	//{
	//	if ( pLAND->ISMONEY_PICKDELAY ( sTargetID.dwID ) )	return E_FAIL;
	//}

	//BOOL bInventoryOpen = FALSE;
	//bInventoryOpen = CInnerInterface::GetInstance().IsInventoryWindowOpen ();
	//if ( sTargetID.emCrow==CROW_ITEM && bInventoryOpen )
	//{
	//	//	¸Þ½ÃÁö ¹ß»ý.
	//	GLMSG::SNETPC_REQ_FIELD_TO_HOLD NetMsg;
	//	NetMsg.dwGlobID = sTargetID.dwID;
	//	NETSENDTOFIELD ( &NetMsg );

	//	//	´ÙÀ½ ¸Þ½ÃÁö Áö¿¬ ÁöÁ¤.
	//	pLAND->SETITEM_PICKDELAY ( sTargetID.dwID );
	//}
	//else
	{
		//	Note : ¸Þ½ÃÁö ¼Û½ÅÀü¿¡ À¯È¿ÇÒÁö¸¦ ¹Ì¸® °Ë»çÇÔ.
		//
		// »ç¸ÁÈ®ÀÎ
		if ( !IsValidBody() )	return E_FAIL;

		//	°Å¸® Ã¼Å©
		const D3DXVECTOR3 &vTarPos = sTargetID.vPos;

		//	°Å¸® Ã¼Å©
		D3DXVECTOR3 vPos;

		if ( bPet )	
		{
			GLPetClient* pMyPet = GLGaeaClient::GetInstance().GetPetClient ();
			if ( pMyPet->IsVALID() )	vPos = pMyPet->GetPosition();
		}	
		else vPos = m_vPos;

		D3DXVECTOR3 vDistance = vPos - vTarPos;
		float fDistance = D3DXVec3Length ( &vDistance );

		WORD wTarBodyRadius = 4;
		WORD wTakeRange = wTarBodyRadius + GETBODYRADIUS() + 2;
		WORD wTakeAbleDis = wTakeRange + 15;

		if ( fDistance>wTakeAbleDis )
		{
			CInnerInterface::GetInstance().PrintMsgText( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMTAKE_FB_DISTANCE") );			
			return E_FAIL;
		}

		if ( sTargetID.emCrow==CROW_ITEM )
		{
			PITEMCLIENTDROP pItemDrop = pLAND->GetItem ( sTargetID.dwID );
			if ( !pItemDrop )		return E_FAIL;

			if( CInnerInterface::GetInstance().GetQBoxButton()->GetQBoxEnable() == FALSE )
			{
				SITEM *pItem = GLItemMan::GetInstance().GetItem(pItemDrop->sItemClient.sNativeID);
				if( pItem != NULL && pItem->sBasicOp.emItemType==ITEM_QITEM )
				{
					CInnerInterface::GetInstance().PrintMsgText( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("QBOX_OPTION_DISABLE_MSG") );
					return E_FAIL;
				}
			}

			//	Note : ÀÎº¥¿¡ ³ÖÀ»¼ö ÀÖ´ÂÁö °Ë»ç.
			//
			BOOL bOk = IsInsertToInven ( pItemDrop );
			if ( !bOk )
			{
#if defined(VN_PARAM) //vietnamtest%%%
				if( m_dwVietnamGainType == GAINTYPE_EMPTY )	return E_FAIL;
#endif
				//	ÀÎ¹êÀÌ °¡µæÂþÀ½.
				//	¸Þ½ÃÁö ¹ß»ý.
				GLMSG::SNETPC_REQ_FIELD_TO_HOLD NetMsg;
				NetMsg.dwGlobID = sTargetID.dwID;
				NETSENDTOFIELD ( &NetMsg );
				return S_OK;
			}

			//	´ÙÀ½ ¸Þ½ÃÁö Áö¿¬ ÁöÁ¤.
			// pLAND->SETITEM_PICKDELAY ( sTargetID.dwID );
		}
		else if ( sTargetID.emCrow==CROW_MONEY )
		{
			PMONEYCLIENTDROP pMoney = GLGaeaClient::GetInstance().GetActiveMap()->GetMoney ( sTargetID.dwID );
			if ( !pMoney )		return E_FAIL;

			//	´ÙÀ½ ¸Þ½ÃÁö Áö¿¬ ÁöÁ¤.
			// pLAND->SETMONEY_PICKDELAY ( sTargetID.dwID );
		}

		//	¸Þ½ÃÁö ¹ß»ý.
		GLMSG::SNETPC_REQ_FIELD_TO_INVEN NetMsg;
		NetMsg.emCrow = sTargetID.emCrow;
		NetMsg.dwID = sTargetID.dwID;
		NetMsg.bPet	= bPet;
		NETSENDTOFIELD ( &NetMsg );
	}

	return S_OK;
}

//	Note : ÀÎº¥Åä¸® ¾ÆÀÌÅÛ µé¶§, ³õÀ»¶§, ±³È¯ÇÒ¶§, ÇÕÄ¥¶§.
HRESULT GLCharacter::ReqInvenTo ( WORD wPosX, WORD wPosY )
{
	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );

	//	Note : Æ®·¡ÀÌµå È°¼ºÈ­½Ã¿¡.
	//
	if ( GLTradeClient::GetInstance().Valid() )
	{
		if ( pInvenItem )	GLTradeClient::GetInstance().SetPreItem ( SINVEN_POS( pInvenItem->wPosX, pInvenItem->wPosY ) );
		else				GLTradeClient::GetInstance().ReSetPreItem ();
		return S_OK;
	}

	if( ValidRebuildOpen() || ValidGarbageOpen() || ValidItemMixOpen() )	// ITEMREBUILD_MARK
	{
		if( m_sPreInventoryItem.wPosX == wPosX && m_sPreInventoryItem.wPosY == wPosY )
		{
			m_sPreInventoryItem.RESET();
		}
		else
		{
			if( pInvenItem )
				m_sPreInventoryItem.SET( wPosX, wPosY );
			else
				m_sPreInventoryItem.RESET();
		}
		return S_OK;
	}

	if ( !VALID_HOLD_ITEM () && !pInvenItem )	return E_FAIL;

	if ( VALID_HOLD_ITEM () && pInvenItem )
	{
#if defined(VN_PARAM) //vietnamtest%%%
		const SITEMCUSTOM& sCustom = GET_HOLD_ITEM();
		
		if ( !sCustom.bVietnamGainItem )
#endif
		{
			GLMSG::SNETPC_REQ_INVEN_EX_HOLD NetMsg;
			NetMsg.wPosX = pInvenItem->wPosX;
			NetMsg.wPosY = pInvenItem->wPosY;
			NETSENDTOFIELD ( &NetMsg );
		}
	}
	else if ( pInvenItem )
	{
		GLMSG::SNETPC_REQ_INVEN_TO_HOLD NetMsg;
		NetMsg.wPosX = pInvenItem->wPosX;
		NetMsg.wPosY = pInvenItem->wPosY;
		NETSENDTOFIELD ( &NetMsg );
	}
	else if ( VALID_HOLD_ITEM () )
	{
#if defined(VN_PARAM) //vietnamtest%%%
		const SITEMCUSTOM& sCustom = GET_HOLD_ITEM();
		
		if ( !sCustom.bVietnamGainItem || ( sCustom.bVietnamGainItem && m_dwVietnamInvenCount > 0 ) )
#endif
		{
			//	Note : ¸Þ½ÃÁö ¼Û½ÅÀü¿¡ À¯È¿ÇÒÁö¸¦ ¹Ì¸® °Ë»çÇÔ.
			//
			SITEM* pItem = GLItemMan::GetInstance().GetItem ( GET_HOLD_ITEM().sNativeID );
			GASSERT(pItem&&"¾ÆÀÌÅÆ ´ëÀÌÅÍ°¡ Á¸Á¦ÇÏÁö ¾ÊÀ½");

			BOOL bOk = m_cInventory.IsInsertable ( pItem->sBasicOp.wInvenSizeX, pItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
			if ( !bOk )
			{
				//	ÀÎ¹êÀÌ °¡µæÂþÀ½.
				return E_FAIL;
			}

			GLMSG::SNETPC_REQ_HOLD_TO_INVEN NetMsg;
			NetMsg.wPosX = wPosX;
			NetMsg.wPosY = wPosY;
#if defined(VN_PARAM) //vietnamtest%%%
			NetMsg.bUseVietnamInven = sCustom.bVietnamGainItem;
#else
			NetMsg.bUseVietnamInven = FALSE;
#endif
			NETSENDTOFIELD ( &NetMsg );

		}
#if defined(VN_PARAM) //vietnamtest%%%
		else
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_VIETNAM_ITEMGET_FAILED") );
			return E_FAIL;		
		}
#endif
	}

	return S_OK;
}

//	Note : º£Æ®³² Å½´Ð ¹æÁö ÀÎº¥Åä¸® ¾ÆÀÌÅÛ µé¶§, ³õÀ»¶§, ±³È¯ÇÒ¶§, ÇÕÄ¥¶§.
HRESULT GLCharacter::ReqVNInvenTo ( WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )							return E_FAIL;
	if ( ValidWindowOpen() )						return E_FAIL;	

	SINVENITEM* pInvenItem = m_cVietnamInventory.FindPosItem ( wPosX, wPosY );
	if ( !VALID_HOLD_ITEM () && !pInvenItem )		return E_FAIL;


	const SITEMCUSTOM& sCustom = GET_HOLD_ITEM();


	if ( VALID_HOLD_ITEM () && pInvenItem )
	{
		if ( sCustom.bVietnamGainItem )
		{
			GLMSG::SNETPC_REQ_VNGAIN_EX_HOLD NetMsg;
			NetMsg.wPosX = pInvenItem->wPosX;
			NetMsg.wPosY = pInvenItem->wPosY;
			NETSENDTOFIELD ( &NetMsg );
		}
	}
	else if ( pInvenItem )
	{
		GLMSG::SNETPC_REQ_VNGAIN_TO_HOLD NetMsg;
		NetMsg.wPosX = pInvenItem->wPosX;
		NetMsg.wPosY = pInvenItem->wPosY;
		NETSENDTOFIELD ( &NetMsg );
	}
	else if ( VALID_HOLD_ITEM () && sCustom.bVietnamGainItem )
	{
		//	Note : ¸Þ½ÃÁö ¼Û½ÅÀü¿¡ À¯È¿ÇÒÁö¸¦ ¹Ì¸® °Ë»çÇÔ.
		//
		SITEM* pItem = GLItemMan::GetInstance().GetItem ( GET_HOLD_ITEM().sNativeID );
		GASSERT(pItem&&"¾ÆÀÌÅÆ ´ëÀÌÅÍ°¡ Á¸Á¦ÇÏÁö ¾ÊÀ½");

		BOOL bOk = m_cVietnamInventory.IsInsertable ( pItem->sBasicOp.wInvenSizeX, pItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
		if ( !bOk )
		{
			//	ÀÎ¹êÀÌ °¡µæÂþÀ½.
			return E_FAIL;
		}

		GLMSG::SNETPC_REQ_HOLD_TO_VNGAIN NetMsg;
		NetMsg.wPosX = wPosX;
		NetMsg.wPosY = wPosY;

		NETSENDTOFIELD ( &NetMsg );
	}
	return S_OK;
}

//	Note : º£Æ®³² ÀÎº¥Åä¸®¿¡¼­ ¿À¸¥ÂÊ ¹öÆ°À¸·Î ¾ÆÀÌÅÛÀ» ¿Å±æ °æ¿ì
HRESULT GLCharacter::ReqVietemInvenTo (WORD wPosX, WORD wPosY )
{

	if( m_dwVietnamInvenCount <= 0 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_VIETNAM_ITEMGET_FAILED") );
		return E_FAIL;
	}

	SINVENITEM* pInvenItem = m_cVietnamInventory.FindPosItem ( wPosX, wPosY );

	if ( !pInvenItem )	return E_FAIL;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			return E_FAIL;

	WORD wINVENX = pItem->sBasicOp.wInvenSizeX;
	WORD wINVENY = pItem->sBasicOp.wInvenSizeY;

	//	Note : ÀÎº¥¿¡ ¿©À¯ °ø°£ÀÌ ÀÖ´ÂÁö °Ë»ç.
	//
	//BOOL bITEM_SPACE(FALSE);
	//if ( pItem->ISPILE() )
	//{
	//	//	°ãÄ§ ¾ÆÀÌÅÛÀÏ °æ¿ì.
	//	WORD wPILENUM = pItem->sDrugOp.wPileNum;
	//	WORD wREQINSRTNUM = ( wBuyNum * pItem->GETAPPLYNUM() );
	//	bITEM_SPACE = m_cInventory.ValidPileInsrt ( wREQINSRTNUM, sBUYNID, wPILENUM, wINVENX, wINVENY );
	//}
	//else
	//{
	//	GASSERT(wBuyNum==1&&"°ãÄ§ÀÌ ºÒ°¡´ÉÇÑ ¾ÆÀÌÅÛÀº 1°³¾¿¸¸ ±¸ÀÔ °¡´ÉÇÕ´Ï´Ù.");

	//	//	ÀÏ¹Ý ¾ÆÀÌÅÛÀÇ °æ¿ì.
	//	WORD wInsertPosX(0), wInsertPosY(0);
	//	bITEM_SPACE = m_cInventory.FindInsrtable ( wINVENX, wINVENY, wInsertPosX, wInsertPosY );
	//}

	WORD wInsertPosX(0), wInsertPosY(0);
	BOOL bITEM_SPACE(FALSE);
	bITEM_SPACE = m_cInventory.FindInsrtable ( wINVENX, wINVENY, wInsertPosX, wInsertPosY );

	if ( !bITEM_SPACE )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMCHARGED_ITEM_GET_FB_NOINVEN") );
		return E_FAIL;
	}

	//	±¸ÀÔ ¿äÃ» ¸Þ½ÃÁö.
	GLMSG::SNETPC_REQ_VNINVEN_TO_INVEN NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//	Note : º£Æ®³² ÀÎº¥Åä¸®ÀÇ ¾ÆÀÌÅÛ ÀüÃ¼ »èÁ¦
HRESULT GLCharacter::ReqVNInveReset ()
{
	m_cVietnamInventory.DeleteItemAll();

	GLMSG::SNETPC_REQ_VNGAIN_INVEN_RESET NetMsg;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

// *****************************************************
// Desc: ¾ÆÀÌÅÛ ÀÎÃ¾Æ® ½Ãµµ
// *****************************************************
HRESULT GLCharacter::ReqGrinding ( WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )						return E_FAIL;
	if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			return E_FAIL;

	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pHold )	return S_FALSE;

	if ( pHold->sBasicOp.emItemType != ITEM_GRINDING )	return S_FALSE;
    //acce ups
	BOOL bGrinding = pItem->sBasicOp.emItemType==ITEM_SUIT && pItem->sSuitOp.wReModelNum>0;
	if ( !bGrinding )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_NOITEM") );
		return S_FALSE;
	}

	if ( pHold->sGrindingOp.emTYPE == EMGRINDING_DAMAGE || pHold->sGrindingOp.emTYPE == EMGRINDING_DEFENSE )
	{
		if ( pInvenItem->sItemCustom.GETGRADE(pHold->sGrindingOp.emTYPE)>=GLCONST_CHAR::wGRADE_MAX )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_MAX") );
			return S_FALSE;
		}
	}
	else
	{
		if ( pInvenItem->sItemCustom.GETGRADE(pHold->sGrindingOp.emTYPE)>=GLCONST_CHAR::wGRADE_MAX_REGI )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_MAX") );
			return S_FALSE;
		}
	}



	//	Note : ¿¬¸¶Á¦ µî±Þ, ³·Àº ¿¬¸¶Á¦·Î ³ôÀº ¿¬¸¶ ºÒ°¡´É
	//
	
	BYTE cGrade = 0;

	cGrade = pInvenItem->sItemCustom.GETGRADE(pHold->sGrindingOp.emTYPE);

	if ( cGrade >= GRADE_HIGH && pHold->sGrindingOp.emGRINDER_TYPE != EMGRINDER_TOP )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_NOT_BEST") );
		return S_FALSE;
	}
	else if ( cGrade >=GRADE_NORMAL && pHold->sGrindingOp.emGRINDER_TYPE < EMGRINDER_HIGH )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_NOT_HIGH") );
		return S_FALSE;
	}


	// ¹æ¾î±¸ ¹× ¹«±â°¡ ÃÖ»óÀ§ µî±Þ±îÁö ÀÎÃ¦Æ®°¡ µÇ´ÂÁö È®ÀÎ
	if ( cGrade >= GRADE_HIGH && pItem->sGrindingOp.emGRINDER_TYPE != EMGRINDER_TOP )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_NOT_BESTITEM") );
		return S_FALSE;
	}

	//	Note : ¿¬¸¶Á¦ ¼ö·® È®ÀÎ
	if ( cGrade >= GRADE_HIGH )
	{
		if ( GLCONST_CHAR::wUSE_GRADE_NUM[cGrade-GRADE_HIGH] > GET_SLOT_ITEM(SLOT_HOLD).wTurnNum )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_NOT_NUM"), GLCONST_CHAR::wUSE_GRADE_NUM[cGrade-GRADE_HIGH]  );
			return S_FALSE;
		}

	}

	//Note : °í±Þ ¿¬¸¶Á¦ÀÏ °æ¿ìµî GRADE_NORMAL ±Þ ¹Ì¸¸ ¿¬¸¶ ºÒ°¡´É.
	//
	//if ( pInvenItem->sItemCustom.GETGRADE(pHold->sGrindingOp.emTYPE)<GRADE_NORMAL && pHold->sGrindingOp.bHIGH )
	//{
	//	CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_NOT_LOW") );
	//	return S_FALSE;
	//}

	if ( pItem->sSuitOp.gdDamage == GLPADATA(0,0) )
	{
		if ( pHold->sGrindingOp.emCLASS != EMGRINDING_CLASS_CLOTH )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_DEFCLASS") );
			return S_FALSE;
		}
	}
	else
	{
		if ( pHold->sGrindingOp.emCLASS != EMGRINDING_CLASS_ARM )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("GRINDING_DEFCLASS") );
			return S_FALSE;
		}
	}

	//	Note : ¼­¹ö¿¡ ¿¬¸¶ ½Ãµµ ¿äÃ».
	//
	GLMSG::SNET_INVEN_GRINDING NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqDisguise ( WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )						return E_FAIL;
	if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			return E_FAIL;

	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pHold )	return S_FALSE;

	if ( !pHold->sBasicOp.IsDISGUISE() )	return S_FALSE;

	if ( pItem->sBasicOp.IsDISGUISE() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_DISGUISE_FB_FAIL") );
		return S_FALSE;
	}

	if ( ( pHold->sBasicOp.dwReqCharClass & pItem->sBasicOp.dwReqCharClass ) == NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_DISGUISE_FB_DEFSUIT") );
		return S_FALSE;
	}
	
	if ( pHold->sBasicOp.emItemType != ITEM_SUIT )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_DISGUISE_FB_NOTSUIT") );
		return S_FALSE;
	}

	if ( pItem->sBasicOp.emItemType != ITEM_SUIT )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_DISGUISE_FB_NOTSUIT") );
		return S_FALSE;
	}

	if ( pHold->sSuitOp.emSuit != pItem->sSuitOp.emSuit )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_DISGUISE_FB_DEFSUIT") );
		return S_FALSE;
	}

	if ( pInvenItem->sItemCustom.nidDISGUISE!=SNATIVEID(false) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_DISGUISE_FB_ALREADY") );
		return S_FALSE;
	}

	GLMSG::SNET_INVEN_DISGUISE	NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqCleanser ( WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )						return E_FAIL;
	if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			return E_FAIL;

	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pHold )	return S_FALSE;

	//if ( pHold->sBasicOp.emItemType!=ITEM_CLEANSER )
	//{
	//	CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_CLEANSER_FB_NOCLEANSER") );
	//	return S_FALSE;
	//}

	if ( pInvenItem->sItemCustom.nidDISGUISE==SNATIVEID(false) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_CLEANSER_FB_NONEED") );
		return S_FALSE;
	}

	GLMSG::SNET_INVEN_CLEANSER	NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqCharCard ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			return E_FAIL;

	//if ( pItem->sBasicOp.emItemType!=ITEM_CHARACTER_CARD )	return E_FAIL;

	GLMSG::SNET_INVEN_CHARCARD NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//	Note : Ã¢°í Ä«µå »ç¿ë.
HRESULT GLCharacter::ReqStorageCard ( WORD wPosX, WORD wPosY, WORD wSTORAGE )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )					return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_STORAGECARD_FB_NOITEM") );
		return E_FAIL;
	}

	//if ( pItem->sBasicOp.emItemType!=ITEM_STORAGE_CARD )
	//{
	//	CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_STORAGECARD_FB_NOITEM") );
	//	return E_FAIL;
	//}

	if ( wSTORAGE < 1 || wSTORAGE>=(EMSTORAGE_CHANNEL-1) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_STORAGECARD_FB_INVNUM") );
		return E_FAIL;
	}

	GLMSG::SNET_INVEN_STORAGECARD NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NetMsg.wSTORAGE = wSTORAGE;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqInvenLineCard ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_INVENLINE_FB_NOITEM") );
		return E_FAIL;
	}

	//if ( pItem->sBasicOp.emItemType!=ITEM_INVEN_CARD )
	//{
	//	CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_INVENLINE_FB_NOITEM") );
	//	return E_FAIL;
	//}

	if ( m_wINVENLINE >= (EM_INVENSIZE_Y-EM_INVEN_DEF_SIZE_Y-1) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_INVENLINE_FB_MAXLINE") );
		return E_FAIL;
	}

	GLMSG::SNET_INVEN_INVENLINE NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqRemodelOpenCard ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if ( ValidRebuildOpen() )					return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_REMODELOPEN_FB_NOITEM") );
		return E_FAIL;
	}

	GLMSG::SNET_INVEN_REMODELOPEN NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqGabargeOpenCard ( WORD wPosX, WORD wPosY )
{
	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_REMODELOPEN_FB_NOITEM") );
		return E_FAIL;
	}

	GLMSG::SNET_INVEN_GARBAGEOPEN NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}


HRESULT GLCharacter::ReqStorageOpenCard ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	// Ã¢°í°¡ ¿­·ÁÀÖÀ¸¸é Ã¢°í ¿¬°áÄ«µå¸¦ »ç¿ëÇÒ ¼ö ¾ø´Ù.
	if ( CInnerInterface::GetInstance().IsStorageWindowOpen () ) return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_STORAGEOPEN_FB_NOITEM") );
		return E_FAIL;
	}

	//if ( pItem->sBasicOp.emItemType!=ITEM_STORAGE_CONNECT )
	//{
	//	CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_STORAGEOPEN_FB_NOITEM") );
	//	return E_FAIL;
	//}

	GLMSG::SNET_INVEN_STORAGEOPEN NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqStorageCloseCard()
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;

	GLMSG::SNET_INVEN_STORAGECLOSE NetMsg;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqPremiumSet ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_PREMIUMSET_FB_NOITEM") );
		return E_FAIL;
	}

	//if ( pItem->sBasicOp.emItemType!=ITEM_PREMIUMSET )
	//{
	//	CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_PREMIUMSET_FB_NOITEM") );
	//	return E_FAIL;
	//}

	GLMSG::SNET_INVEN_PREMIUMSET NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqInvenHairChange ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_HAIR_CHANGE_FB_NOITEM") );
		return E_FAIL;
	}

	//if ( pItem->sBasicOp.emItemType!=ITEM_HAIR )
	//{
	//	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_HAIR_CHANGE_FB_NOITEM") );
	//	return E_FAIL;
	//}

	GLMSG::SNETPC_INVEN_HAIR_CHANGE NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

// *****************************************************
// Desc: ¸Ó¸® ½ºÅ¸ÀÏ º¯°æ
// *****************************************************
HRESULT GLCharacter::ReqInvenHairStyleChange ( WORD wHairStyle )
{
	GLMSG::SNETPC_INVEN_HAIRSTYLE_CHANGE NetMsg;
	NetMsg.wPosX	  = m_wInvenPosX1;
	NetMsg.wPosY	  = m_wInvenPosY1;
	NetMsg.wHairStyle = wHairStyle;
	NETSENDTOFIELD ( &NetMsg );

	m_wInvenPosX1 = 0;
	m_wInvenPosY1 = 0;

	return S_OK;
}

// *****************************************************
// Desc: ¸Ó¸® ½ºÅ¸ÀÏ º¯°æ
// *****************************************************
HRESULT GLCharacter::InvenHairStyleChange ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_HAIR_CHANGE_FB_NOITEM") );
		return E_FAIL;
	}

	//if ( pItem->sBasicOp.emItemType!=ITEM_HAIR_STYLE )
	//{
	//	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_HAIR_CHANGE_FB_NOITEM") );
	//	return E_FAIL;
	//}

	CInnerInterface::GetInstance().ShowGroupFocus( HAIRSTYLECARD_WINDOW );

	m_wInvenPosX1 = wPosX;
	m_wInvenPosY1 = wPosY;

	return S_OK;
}

// *****************************************************
// Desc: ¸Ó¸® »ö±ò º¯°æ
// *****************************************************
HRESULT GLCharacter::ReqInvenHairColorChange ( WORD wHairColor )
{
	GLMSG::SNETPC_INVEN_HAIRCOLOR_CHANGE NetMsg;
	NetMsg.wPosX	  = m_wInvenPosX2;
	NetMsg.wPosY	  = m_wInvenPosY2;
	NetMsg.wHairColor = wHairColor;
	NETSENDTOFIELD ( &NetMsg );

	m_wInvenPosX2 = 0;
	m_wInvenPosY2 = 0;

	return S_OK;
}

// *****************************************************
// Desc: ¸Ó¸® »ö±ò º¯°æ
// *****************************************************
HRESULT GLCharacter::InvenHairColorChange ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_HAIR_CHANGE_FB_NOITEM") );
		return E_FAIL;
	}

	//if ( pItem->sBasicOp.emItemType!=ITEM_HAIR_COLOR )
	//{
	//	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_HAIR_CHANGE_FB_NOITEM") );
	//	return E_FAIL;
	//}

	DoModal( ID2GAMEINTEXT("MODAL_HAIRCOLOR_INFO"), MODAL_INFOMATION, OK, MODAL_HAIRCOLOR_INFO );

	m_wInvenPosX2 = wPosX;
	m_wInvenPosY2 = wPosY;

	return S_OK;
}

//	Note : ¾ó±¼½ºÅ¸ÀÏ º¯°æ.
HRESULT GLCharacter::ReqInvenFaceChange ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_FACE_CHANGE_FB_NOITEM") );
		return E_FAIL;
	}

	//if ( pItem->sBasicOp.emItemType!=ITEM_FACE )
	//{
	//	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_FACE_CHANGE_FB_NOITEM") );
	//	return E_FAIL;
	//}

	GLMSG::SNETPC_INVEN_FACE_CHANGE NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

// *****************************************************
// Desc: ¾ó±¼ ½ºÅ¸ÀÏ º¯°æ
// *****************************************************
HRESULT GLCharacter::ReqInvenFaceStyleChange ( WORD wFaceStyle )
{
	GLMSG::SNETPC_INVEN_FACESTYLE_CHANGE NetMsg;
	NetMsg.wPosX	  = m_wInvenPosX1;
	NetMsg.wPosY	  = m_wInvenPosY1;
	NetMsg.wFaceStyle = wFaceStyle;
	NETSENDTOFIELD ( &NetMsg );

	m_wInvenPosX1 = 0;
	m_wInvenPosY1 = 0;

	return S_OK;
}

// *****************************************************
// Desc: ¾ó±¼ ½ºÅ¸ÀÏ º¯°æ
// *****************************************************
HRESULT GLCharacter::InvenFaceStyleChange ( WORD wPosX, WORD wPosY )
{
	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_FACE_CHANGE_FB_NOITEM") );
		return E_FAIL;
	}

	CInnerInterface::GetInstance().ShowGroupFocus( FACE_CHANGE_WINDOW );

	m_wInvenPosX1 = wPosX;
	m_wInvenPosY1 = wPosY;	

	return S_OK;
}

HRESULT GLCharacter::InvenGenderChange ( WORD wPosX, WORD wPosY )
{
	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_GENDER_CHANGE_FB_NOITEM") );
		return E_FAIL;
	}

	if ( pItem->sBasicOp.emItemType != ITEM_GENDER_CHANGE )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_GENDER_CHANGE_FB_ITEMTYPE") );
		return E_FAIL;
	}

	//added by SeiferXIII009 | 08-07-2012 | make extreme can use gender card
	/*if ( m_emClass == GLCC_EXTREME_M || m_emClass == GLCC_EXTREME_W )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_GENDER_CHANGE_FB_BADCLASS") );
		return E_FAIL;
	}*/

	DoModal( ID2GAMEINTEXT("MODAL_GENDER_CHANGE"),  MODAL_INFOMATION, YESNO, MODAL_GENDER_CHANGE );	

	m_wInvenPosX1 = wPosX;
	m_wInvenPosY1 = wPosY;

	return S_OK;
}

HRESULT GLCharacter::ReqInvenGenderChange ( WORD wFace, WORD wHair )
{
	GLMSG::SNETPC_INVEN_GENDER_CHANGE NetMsg;
	NetMsg.wPosX = m_wInvenPosX1;
	NetMsg.wPosY = m_wInvenPosY1;
	NetMsg.wFace = wFace;	
	NetMsg.wHair = wHair;
	NETSENDTOFIELD ( &NetMsg );

	m_wInvenPosX1 = 0;
	m_wInvenPosY1 = 0;

	return S_OK;
}


HRESULT GLCharacter::InvenRename ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_RENAME_FB_NOITEM") );
		return E_FAIL;
	}

	//if ( pItem->sBasicOp.emItemType!=ITEM_RENAME )
	//{
	//	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_RENAME_FB_BADITEM") );
	//	return E_FAIL;
	//}

	DoModal ( ID2GAMEINTEXT("INVEN_CHAR_RENAME"), MODAL_INPUT, EDITBOX, MODAL_CHAR_RENAME);

	m_wInvenPosX1 = wPosX;
	m_wInvenPosY1 = wPosY;

	return S_OK;
}

HRESULT GLCharacter::ReqInvenRename ( const char* szCharName )
{
	if( !szCharName )
		return S_FALSE;

	CString strTEMP( szCharName );

#ifdef TH_PARAM	
	// ÅÂ±¹¾î ¹®ÀÚ Á¶ÇÕ Ã¼Å©

	if ( !m_pCheckString ) return S_FALSE;

	if ( !m_pCheckString(strTEMP) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMINVEN_RENAME_FB_THAICHAR_ERROR"));
		return S_FALSE;
	}
#endif

#ifdef VN_PARAM
	// º£Æ®³² ¹®ÀÚ Á¶ÇÕ Ã¼Å© 
	if( STRUTIL::CheckVietnamString( strTEMP ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMINVEN_RENAME_FB_VNCHAR_ERROR"));
		return S_FALSE;
	}

#endif 


	BOOL bFILTER0 = STRUTIL::CheckString( strTEMP );
	BOOL bFILTER1 = CRanFilter::GetInstance().NameFilter( strTEMP );
	if ( bFILTER0 || bFILTER1 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEEXTEXT("CHARACTER_BADNAME") );
		return S_FALSE;
	}

	GLMSG::SNETPC_INVEN_RENAME NetMsg;
	NetMsg.wPosX = m_wInvenPosX1;
	NetMsg.wPosY = m_wInvenPosY1;
	StringCchCopy ( NetMsg.szName, CHAR_SZNAME, szCharName );
	NETSENDTOFIELD ( &NetMsg );

	m_wInvenPosX1 = 0;
	m_wInvenPosY1 = 0;
	return S_OK;
}

//	Note : ½ºÅ³ ½ºÅÝ ¸®¼Â.
HRESULT GLCharacter::ResetSkillStats ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_RESET_SKST_FB_NOITEM") );
		return E_FAIL;
	}

	DoModal( ID2GAMEINTEXT("MODAL_RESET_SKILLSTATS"),  MODAL_INFOMATION, YESNO, MODAL_RESET_SKILLSTATS );

	m_wInvenPosX1 = wPosX;
	m_wInvenPosY1 = wPosY;

	return S_OK;
}

HRESULT GLCharacter::ReqResetSkillStats ()
{
	GLMSG::SNET_INVEN_RESET_SKST NetMsg;
	NetMsg.wPosX = m_wInvenPosX1;
	NetMsg.wPosY = m_wInvenPosY1;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}
HRESULT GLCharacter::ReqReqBoxOpen ()
{
	GLMSG::SNET_INVEN_BOXOPEN NetMsg;
	NetMsg.wPosX = m_wInvenPosX1;
	NetMsg.wPosY = m_wInvenPosY1;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//	Note : º£Æ®³² ¾ÆÀÌÅÛ, °æÇèÄ¡ È¹µæ Ä«µå »ç¿ë
HRESULT GLCharacter::ReqInvenVietnamGet ( WORD wPosX, WORD wPosY, bool bGetExp )
{
//	if( m_dwVietnamGainType == GAINTYPE_EMPTY ) return E_FAIL;
//	if( m_dwVietnamGainType == GAINTYPE_HALF ) return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		if( bGetExp )
		{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_VIETNAM_EXPGET_FB_NOITEM") );
		}else{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_VIETNAM_ITEMGET_FB_NOITEM") );
		}
		return E_FAIL;
	}

	GLMSG::SNETPC_INVEN_VIETNAM_INVENGET NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NetMsg.bGetExp = bGetExp;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//
HRESULT GLCharacter::ReqEquipItem ( WORD wPosX, WORD wPosY, EMSLOT emSlot )
{
	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );

	if ( !pInvenItem )	return E_FAIL;
	if ( VALID_HOLD_ITEM () ) return E_FAIL;

	// Mike915 Right Click to Equip 3:58 AM 7/17/2013 ÓÒ»÷
		if ( IsACTION(GLAT_ATTACK) || IsACTION(GLAT_SKILL) ||  m_sPMarket.IsOpen() )
		{
			return E_FAIL;
		}

	//	Note : Æ®·¡ÀÌµå È°¼ºÈ­½Ã¿¡.
	//
		BOOL bOk;
		bOk = ACCEPT_ITEM ( pInvenItem->sItemCustom.sNativeID );
		if ( !bOk )
		{
			//	Âø¿ëÁ¶°Ç °Ë»ç.
			return E_FAIL;
		}

		bOk = CHECKSLOT_ITEM ( pInvenItem->sItemCustom.sNativeID, emSlot );
		if ( !bOk )
		{
			//	ÇØ´ç½½·Ô°ú ¸ÂÁö ¾Ê½À´Ï´Ù.
			return E_FAIL;
		}


	if ( GLTradeClient::GetInstance().Valid() )
	{
		if ( pInvenItem )	GLTradeClient::GetInstance().SetPreItem ( SINVEN_POS( pInvenItem->wPosX, pInvenItem->wPosY ) );
		else				GLTradeClient::GetInstance().ReSetPreItem ();
		return S_OK;
	}

	if( ValidRebuildOpen() || ValidGarbageOpen() || ValidItemMixOpen() )	// ITEMREBUILD_MARK
	{
		if( m_sPreInventoryItem.wPosX == wPosX && m_sPreInventoryItem.wPosY == wPosY )
		{
			m_sPreInventoryItem.RESET();
		}
		else
		{
			if( pInvenItem )
				m_sPreInventoryItem.SET( wPosX, wPosY );
			else
				m_sPreInventoryItem.RESET();
		}
		return S_OK;
	}
	if ( pInvenItem->sItemCustom.IsWrap() ) // wrapper
		return S_FALSE;

		GLMSG::SNETPC_REQ_INVEN_TO_HOLD NetMsg;
		NetMsg.wPosX = pInvenItem->wPosX;
		NetMsg.wPosY = pInvenItem->wPosY;
		NETSENDTOFIELD ( &NetMsg );
	
	if ( emSlot == SLOT_RHAND ){
		emSlot = GetCurRHand();
	}
	if ( emSlot == SLOT_LHAND ) {
		emSlot = GetCurLHand();
	}

	bool bEMPTY_SLOT = ISEMPTY_SLOT(pInvenItem->sItemCustom.sNativeID,emSlot);

	if (!bEMPTY_SLOT){
		GLMSG::SNETPC_REQ_SLOT_EX_HOLD NetMsgEquip;
		NetMsgEquip.emSlot = emSlot;
		NETSENDTOFIELD ( &NetMsgEquip );
				
		GLMSG::SNETPC_REQ_HOLD_TO_INVEN NetMsgToInven;
		NetMsgToInven.wPosX = wPosX;
		NetMsgToInven.wPosY = wPosY;

		#if defined(VN_PARAM)
		NetMsgToInven.bUseVietnamInven = sCustom.bVietnamGainItem;
		#else
		NetMsgToInven.bUseVietnamInven = FALSE;
		#endif
		NETSENDTOFIELD ( &NetMsgToInven );
	}else{
		GLMSG::SNETPC_REQ_HOLD_TO_SLOT NetMsgEquip;
		NetMsgEquip.emSlot = emSlot;
		NETSENDTOFIELD ( &NetMsgEquip );	
	}

	return S_OK;
}

HRESULT GLCharacter::ReqUnEquipItem ( EMSLOT emSlot )
{
//	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
if ( !VALID_HOLD_ITEM() && !VALID_SLOT_ITEM(emSlot) )	return E_FAIL;
if ( ValidWindowOpen() )								return E_FAIL;	
//  Right Click to UnEquip 3:58 AM 7/17/2013 ÓÒ»÷
		if ( IsACTION(GLAT_ATTACK) || IsACTION(GLAT_SKILL) ||  m_sPMarket.IsOpen() )
		{
			return E_FAIL;
		}
		bool bOk(false);
		WORD X,Y;
		for ( int x = 0; x < EM_INVENSIZE_X; ++x ) {
		if (bOk) break;
		for (int y=0; y<EM_INVENSIZE_Y; ++y ) {
		if ( m_cInventory.IsInsertable ( 1,1,x,y)){
				Y = y;
				X = x;
				bOk = true;
				break;
		}
		}
		}

		GLMSG::SNETPC_REQ_SLOT_TO_HOLD NetMsg;
		NetMsg.emSlot = emSlot;
		NETSENDTOFIELD ( &NetMsg );

		GLMSG::SNETPC_REQ_SLOT_TO_HOLD NetMsgEquip;
		NetMsgEquip.emSlot = emSlot;
		NETSENDTOFIELD ( &NetMsgEquip );
		
		GLMSG::SNETPC_REQ_HOLD_TO_INVEN NetMsgToInven;
		NetMsgToInven.wPosX = X;
		NetMsgToInven.wPosY = Y;
		#if defined(VN_PARAM)
		NetMsgToInven.bUseVietnamInven = sCustom.bVietnamGainItem;
		#else
		NetMsgToInven.bUseVietnamInven = FALSE;
		#endif
		NETSENDTOFIELD ( &NetMsgToInven );

	return S_OK;
}
//	Note : ÀÎº¥Åä¸® ¾ÆÀÌÅÛ »ç¿ëÇÒ¶§ ( ¸¶½Ã±â, ½ºÅ³¹è¿ì±â µî ).
HRESULT GLCharacter::ReqInvenDrug ( WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )						return E_FAIL;
	if ( ValidWindowOpen() )					return E_FAIL;	

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;	

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )		return E_FAIL;

	PLANDMANCLIENT pLand = GLGaeaClient::GetInstance().GetActiveMap();
	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );	

	if ( pItem->sBasicOp.emItemType == ITEM_CURE )
	{
		//	Note : pk µî±ÞÀÌ »ìÀÎ¸¶ µî±Þ ÀÌ»óÀÏ °æ¿ì È¸º¹¾àÀÇ »ç¿ëÀ» ¸·´Â´Ù.
		//
		PLANDMANCLIENT pLandManClient = GLGaeaClient::GetInstance().GetActiveMap ();
		if ( pLandManClient && !pLandManClient->IsInstantMap() )
		{
			if ( pItem->sDrugOp.emDrug == ITEM_DRUG_HP || 
				pItem->sDrugOp.emDrug == ITEM_DRUG_HP_MP || 
				pItem->sDrugOp.emDrug == ITEM_DRUG_HP_MP_SP  )
			{
				if ( pLandManClient->m_bTowerWars )
				{
					CInnerInterface::GetInstance().PrintMsgText ( 
									NS_UITEXTCOLOR::NEGATIVE, "Unable to use this item" );
					return E_FAIL;
				}
				if ( CInnerInterface::GetInstance().IsVisibleGroup( STORAGE_WINDOW ) )
				{
					CInnerInterface::GetInstance().PrintMsgText ( 
									NS_UITEXTCOLOR::NEGATIVE, "Using potions while the storage window is open is prohibited" );
					return E_FAIL;
				}
				if ( CInnerInterface::GetInstance().IsVisibleGroup (STORAGE_CHARGE_CARD))
				{
					CInnerInterface::GetInstance().PrintMsgText ( 
									NS_UITEXTCOLOR::NEGATIVE, "Using potions while the storage window is open is prohibited" );
					return E_FAIL;
				}
			}
		}

		// ·¹º§ Á¶°Ç È®ÀÎ
		if ( !SIMPLE_CHECK_ITEM( pItem->sBasicOp.sNativeID ) ) return S_FALSE;

		DWORD dwPK_LEVEL = GET_PK_LEVEL();
		if ( dwPK_LEVEL != UINT_MAX && dwPK_LEVEL>GLCONST_CHAR::dwPK_DRUG_ENABLE_LEVEL )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_DRUG_PK_LEVEL") );
			return E_FAIL;
		}

		if ( m_sCONFTING.IsFIGHTING() && !m_sCONFTING.IsRECOVE() )
		{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("CONFRONT_RECOVE"), 0 );
			return E_FAIL;
		}

		if ( CheckCoolTime( pItem->sBasicOp.sNativeID ) ) return E_FAIL;

		if ( pItem->sDrugOp.emDrug!=ITEM_DRUG_NUNE )
		{
		// Prohibits HP Containing Potion on SW MAP - Eduj kun
	        
			switch ( pItem->sDrugOp.emDrug )
			{
			case ITEM_DRUG_HP:// Prohibits HP Containing Potion on SW MAP - Eduj kun
			{	// 9/13/2016 - Anti  Auto Pots System - Eduj
                PLANDMANCLIENT pLAND = GLGaeaClient::GetInstance().GetActiveMap ();

                if ( !pLandMClient )								return S_FALSE;
				bool bSchoolWar = pLandMClient->m_bSchoolWar;
				bool bSchoolWarPVP = pLandMClient->m_bSchoolWarPVP;
				bool bRoyalRumble = pLandMClient->m_bRoyalRumble;
				if ( bSchoolWar || bSchoolWarPVP ) return S_FALSE;

                
			     m_bUsePots = TRUE;
                if ( m_bUsePots ) m_dwTickCount++;
				else m_dwTickCount = 0;
				if ( m_bDisablePots )
				{
				if ( !m_bDetectOnce )
				{
		        CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Force Disconnection After 10sec." );
                     m_bDetectOnce = TRUE;
				}
				return S_FALSE; 
				}
				if ( m_sHP.wMax == m_sHP.wNow ){		return S_FALSE; 
				}else if ( pLAND->m_bSchoolWar ){ 
		        CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Using HP Containing Potions are Prohibited in this area" );
				return S_FALSE; 
		        }
			}
				break;
			case ITEM_DRUG_MP:
				if ( m_sMP.wMax == m_sMP.wNow )		return S_FALSE;
				break;

			case ITEM_DRUG_SP:
				if ( m_sSP.wMax == m_sSP.wNow )		return S_FALSE;
				break;

			case ITEM_DRUG_HP_MP:// Prohibits HP Containing Potion on SW MAP - Eduj kun
				{// 9/13/2016 - Anti  Auto Pots System - Eduj
			     m_bUsePots = TRUE;
                if ( m_bUsePots ) m_dwTickCount++;
				else m_dwTickCount = 0;
				if ( m_bDisablePots )
				{
				if ( !m_bDetectOnce )
				{
		        CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Force Disconnection After 10sec." );
                     m_bDetectOnce = TRUE;
				}
				return S_FALSE; 
				}
				if ( m_sHP.wMax==m_sHP.wNow && m_sMP.wMax==m_sMP.wNow ){		return S_FALSE;
		        }else if ( pLAND->m_bSchoolWar ){ 
		         CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Using HP Containing Potions are Prohibited in this area" );
				return S_FALSE; 
		           }
				}
				break;
			case ITEM_DRUG_MP_SP:
				if ( m_sMP.wMax==m_sMP.wNow && m_sSP.wMax==m_sSP.wNow )		return S_FALSE;
				break;

			case ITEM_DRUG_HP_MP_SP:
				{// 9/13/2016 - Anti  Auto Pots System - Eduj
			     m_bUsePots = TRUE;
                if ( m_bUsePots ) m_dwTickCount++;
				else m_dwTickCount = 0;
				if ( m_bDisablePots )
				{
				if ( !m_bDetectOnce )
				{
		        CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Force Disconnection After 10sec." );
                     m_bDetectOnce = TRUE;
				}
				return S_FALSE; 
				}
				if ( m_sHP.wMax==m_sHP.wNow && m_sMP.wMax==m_sMP.wNow
					&& m_sSP.wMax==m_sSP.wNow ){		return S_FALSE;
		}else if ( pLAND->m_bSchoolWar ){ 

		CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Using HP Containing Potions are Prohibited in this area" );
				return S_FALSE; 
		}
				}
				break;

			case ITEM_DRUG_CURE:
				if ( !ISSTATEBLOW() )				return S_FALSE;
				break;

			case ITEM_DRUG_HP_CURE:
				{// 9/13/2016 - Anti  Auto Pots System - Eduj
			     m_bUsePots = TRUE;
                if ( m_bUsePots ) m_dwTickCount++;
				else m_dwTickCount = 0;
				if ( m_bDisablePots )
				{
				if ( !m_bDetectOnce )
				{
		        CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Force Disconnection After 10sec." );
                     m_bDetectOnce = TRUE;
				}
				return S_FALSE; 
				}
				if ( m_sHP.wMax == m_sHP.wNow && !ISSTATEBLOW() ) {		return S_FALSE;
		}else if ( pLAND->m_bSchoolWar ){ 
		CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Using HP Containing Potions are Prohibited in this area" );

				return S_FALSE; 
		}
				}
				break;

			case ITEM_DRUG_HP_MP_SP_CURE:
				{// 9/13/2016 - Anti  Auto Pots System - Eduj
			     m_bUsePots = TRUE;
                if ( m_bUsePots ) m_dwTickCount++;
				else m_dwTickCount = 0;
				if ( m_bDisablePots )
				{
				if ( !m_bDetectOnce )
				{
		        CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Force Disconnection After 10sec." );
                     m_bDetectOnce = TRUE;
				}
				return S_FALSE; 
				}
				if ( m_sHP.wMax==m_sHP.wNow && m_sMP.wMax==m_sMP.wNow
					&& m_sSP.wMax==m_sSP.wNow && !ISSTATEBLOW() ) {		return S_FALSE;
		}else if ( pLAND->m_bSchoolWar ){ 
		CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Using HP Containing Potions are Prohibited in this area" );

				return S_FALSE; 
		}
				}
				break;
			};

			GLMSG::SNETPC_REQ_INVENDRUG NetMsg;
			NetMsg.wPosX = wPosX;
			NetMsg.wPosY = wPosY;

			NETSENDTOFIELD ( &NetMsg );

			return S_OK;
		}
	}

	PLANDMANCLIENT pLandManClient = GLGaeaClient::GetInstance().GetActiveMap ();
	if ( !pHold )
	{
         PLANDMANCLIENT pLAND = GLGaeaClient::GetInstance().GetActiveMap ();
		// ·¹º§ Á¶°Ç È®ÀÎ
		if ( !SIMPLE_CHECK_ITEM( pItem->sBasicOp.sNativeID ) ) return S_FALSE;

		if ( CheckCoolTime( pItem->sBasicOp.sNativeID ) ) return S_FALSE;

		if ( pItem->sBasicOp.emItemType == ITEM_SKILL )
		{
			ReqInvenSkill ( wPosX, wPosY );
		}
        else if (  pItem->sBasicOp.emItemType == ITEM_RECALL )
		{
			ReqReCall ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_TELEPORT_CARD )
		{
			ReqTeleport ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_BOX )
		{
			ReqBoxOpen ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_RANDOMITEM )
		{
			ReqRandumBoxOpen ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_CHARACTER_CARD )
		{
			ReqCharCard ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_STORAGE_CARD )
		{
			//	Note : ÀÎÅÍÆäÀÌ½º¿¡ ÃæÀüÇÒ Ã¢°í ¼±ÅÃÇÏ°Ô.
			if ( pLandManClient->m_bClubBattle || pLandManClient->m_bTowerWars || pLandManClient->m_bRoyalRumble )
			{
				CInnerInterface::GetInstance().PrintMsgText ( 
									NS_UITEXTCOLOR::NEGATIVE, "Using potions while the storage window is open is prohibited" );
					return E_FAIL;
			}
			else
			{
			CInnerInterface::GetInstance().SetStorageChargeOpen ( wPosX, wPosY );
			}
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_INVEN_CARD )
		{
			ReqInvenLineCard ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_STORAGE_CONNECT )
		{
			if ( pLandManClient->m_bClubBattle || pLandManClient->m_bTowerWars || pLandManClient->m_bRoyalRumble )
			{
				CInnerInterface::GetInstance().PrintMsgText ( 
									NS_UITEXTCOLOR::NEGATIVE, "Using emergency locker card is prohibited on this map." );
					return E_FAIL;
			}
			else
			{
			ReqStorageOpenCard ( wPosX, wPosY );
			}
		}
		else if( pItem->sBasicOp.emItemType == ITEM_REMODEL )
		{
			if ( m_fRandoDelay >= 5.0f )
			{
				m_fRandoDelay = 0.0f;
				ReqRemodelOpenCard ( wPosX, wPosY );
			}
		//m_fRandoDelay >= 5.0f ? m_fRandoDelay = 0.0f , ReqRemodelOpenCard ( wPosX, wPosY ) : 
		//CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "Please Wait." );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_GARBAGE_CARD )
		{
			ReqGabargeOpenCard ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_PREMIUMSET )
		{
			ReqPremiumSet ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_SKP_STAT_RESET )
		{
			ResetSkillStats ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_PRIVATEMARKET )
		{
			// ¸ØÃçÀÖÁö ¾ÊÀ¸¸é »óÁ¡ °³¼³ ºÒ°¡
			if ( !IsACTION ( GLAT_IDLE ) || m_sREACTION.ISVALID() )
			{
				CInnerInterface::GetInstance().PrintMsgTextDlg ( 
					NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_OPEN_FB_NOIDLE") );
				return S_FALSE;
			}

			m_wPMPosX = (wPosX);
			m_wPMPosY = (wPosY);

			//	Note : ÀÎÅÍÆäÀÌ½º¿¡ °³ÀÎ »óÁ¡ ½ÃÀÛ.
			CInnerInterface::GetInstance().SetPrivateMarketMake ();
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_HAIR )
		{
			ReqInvenHairChange ( wPosX, wPosY );
		}

		else if ( pItem->sBasicOp.emItemType==ITEM_HAIR_COLOR )
		{
			InvenHairColorChange ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_SCHOOL_CHANGE_SG )
		{
			InvenSchoolChangeSG ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_SCHOOL_CHANGE_MP )
		{
			InvenSchoolChangeMP ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_SCHOOL_CHANGE_PH )
		{
			InvenSchoolChangePH ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_HAIR_STYLE )
		{
			InvenHairStyleChange ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_FACE )
		{
			ReqInvenFaceChange( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_FACE_STYLE )
		{
			InvenFaceStyleChange( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_TAXI_CARD )
		{
			InvenUseTaxiCard( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_NPC_RECALL )
		{
			InvenUseNpcRecall( wPosX, wPosY );
		}			
		else if ( pItem->sBasicOp.emItemType==ITEM_GENDER_CHANGE )
		{
			InvenGenderChange ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_RENAME )
		{
		if ( CInnerInterface::GetInstance().IsVisibleGroup( INVENTORY_WINDOW ) ) CInnerInterface::GetInstance().HideGroup( INVENTORY_WINDOW );
		else CInnerInterface::GetInstance().HideGroup( INVENTORY_WINDOW );
			InvenRename ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType ==ITEM_VIETNAM_EXPGET )
		{
			ReqInvenVietnamGet ( wPosX, wPosY, TRUE );
		}
		else if ( pItem->sBasicOp.emItemType ==ITEM_VIETNAM_ITEMGET )
		{
			ReqInvenVietnamGet ( wPosX, wPosY, FALSE );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_PET_RENAME )
		{
			GLGaeaClient::GetInstance().GetPetClient()->ReqInputName ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_PET_COLOR )
		{
			GLGaeaClient::GetInstance().GetPetClient()->ReqInputColor ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_PET_STYLE )
		{
			GLGaeaClient::GetInstance().GetPetClient()->ReqInputStyle ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_PET_SKILL )
		{
			GLGaeaClient::GetInstance().GetPetClient()->ReqLearnSkill ( wPosX, wPosY );
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_SUIT  ||
				  pItem->sBasicOp.emItemType==ITEM_ARROW ||
				  pItem->sBasicOp.emItemType==ITEM_CHARM ||
				  pItem->sBasicOp.emItemType==ITEM_BULLET  )
		{
			SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
			EMSLOT i;
			i = SLOT_TSIZE;
			if ( pInvenItem )
			{
				if ( pInvenItem->sItemCustom.IsWrap() )
					ReqRemoveWrap( wPosX, wPosY ); // wrapper
				else
					ReqEquipItem( wPosX, wPosY, i);
			}
		}
		else if ( pItem->sBasicOp.emItemType==ITEM_PET_CARD )
		{
			// ÆÖÀÌ ¼ÒÈ¯µÇ¾î ÀÖÀ¸¸é
			if ( CInnerInterface::GetInstance().IsPetWindowOpen())
			{
				CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "Close the Pet Window first.");
				return S_FALSE;
			}

			if ( m_fPetDelay >= 5.0f )	//add pet delay by DarkEagle
			{
				if ( GLGaeaClient::GetInstance().GetPetClient()->IsVALID() )
				{
					GLMSG::SNETPET_REQ_UNUSEPETCARD NetMsg;
					NetMsg.dwGUID = GLGaeaClient::GetInstance().GetPetClient()->m_dwGUID;
					NetMsg.dwPetID = pInvenItem->sItemCustom.dwPetID;
					NETSENDTOFIELD ( &NetMsg );
				}
				else 
				{
					ReqUsePetCard ( wPosX, wPosY );
					m_wInvenPosX1 = wPosX;
					m_wInvenPosY1 = wPosY;
				}

				m_fPetDelay = 0.0f;
			}else{
				CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "Please wait for 5 seconds.");
			}

			return S_OK;
		}
		else if ( pItem->sBasicOp.emItemType == ITEM_DUAL_PETCARD)
		{
			GLGaeaClient::GetInstance().GetPetClient()->ReqDualSkillPet(wPosX, wPosY);
		}
	}
	// ¼Õ¿¡ ¹«¾ð°¡ µé¾úÀ» °æ¿ì
	else	
	{

		// ·¹º§ Á¶°Ç Ã¼Å© ¾ÈÇÏ±â À§ÇØ
		if ( pHold->sBasicOp.IsDISGUISE() )
		{
			ReqDisguise ( wPosX, wPosY );
			return S_OK;
		}

		// ·¹º§ Á¶°Ç È®ÀÎ
		if ( !SIMPLE_CHECK_ITEM( pHold->sBasicOp.sNativeID ) ) return S_FALSE;

		if ( CheckCoolTime( pHold->sBasicOp.sNativeID ) ) return S_FALSE;

		// ÆÖºÎÈ°Ä«µå¸¦ ¹Ù¸¦°æ¿ì
		if ( pHold->sBasicOp.emItemType == ITEM_PET_REVIVE )
		{
			if ( pItem->sBasicOp.emItemType!=ITEM_PET_CARD )	return S_FALSE;
			
			m_wInvenPosX1 = wPosX;
			m_wInvenPosY1 = wPosY;
			
			m_sTempItemCustom = GET_HOLD_ITEM ();
			RELEASE_HOLD_ITEM ();

			// ¿©±â¼­ ÆÖºÎÈ°¸Þ½ÃÁöÃ¢À» ¶ç¿î´Ù.
			if ( !CInnerInterface::GetInstance().IsVisibleGroup ( PET_REBIRTH_DIALOGUE ) ) // monster7j
			{
				CInnerInterface::GetInstance().ShowGroupFocus ( PET_REBIRTH_DIALOGUE );
			}
		}
		else if ( pHold->sBasicOp.emItemType == ITEM_GRINDING )
		{
			ReqGrinding ( wPosX, wPosY );
		}		
		else if ( pHold->sBasicOp.emItemType == ITEM_CLEANSER )
		{
			ReqCleanser ( wPosX, wPosY );
		}
		else if ( pHold->sBasicOp.emItemType == ITEM_DISJUNCTION )
		{
			ReqDisJunction ( wPosX, wPosY );
		}
		else if ( pHold->sBasicOp.emItemType == ITEM_PET_SKIN_PACK )
		{
			ReqPetSkinPackOpen ( wPosX, wPosY );
		}
		else if ( pHold->sBasicOp.emItemType == ITEM_PET_FOOD )
		{
			GLGaeaClient::GetInstance().GetPetClient()->ReqGiveFood ( wPosX, wPosY );
		}
		else if ( pHold->sBasicOp.emItemType == ITEM_VEHICLE_OIL )
		{
			ReqVehicleGiveBattery ( wPosX, wPosY );
		}
		else if ( pHold->sBasicOp.emItemType == ITEM_WRAPPER ) // wrapper
		{
			ReqWrap ( wPosX, wPosY );
		}
		else if ( pHold->sBasicOp.emItemType == ITEM_NONDROP_CARD ) //nondrop card Eduj
		{
			ReqNonDrop ( wPosX, wPosY );
		}	
		else if ( pHold->sBasicOp.emItemType == ITEM_MAX_RV_CARD ) //add max rv card - Eduj
		{
			ReqMaxRV ( wPosX, wPosY );
		}	
	}

	return S_OK;
}

DWORD GLCharacter::GetAmountActionQ ( WORD wSLOT )
{
	if ( GLTradeClient::GetInstance().Valid() )	return 0;
	if ( EMACTIONQUICK_SIZE <= wSLOT )		return 0;
	
	const SACTION_SLOT &sACTION = m_sACTIONQUICK[wSLOT];

	return m_cInventory.CountTurnItem ( sACTION.sNID );
}

HRESULT GLCharacter::ReqSchoolWarParticipate ()
{
	if ( IsACTION(GLAT_ATTACK) || IsACTION(GLAT_SKILL) ||  m_sPMarket.IsOpen() )	return E_FAIL;
	bool bClientParticipate = GLGaeaClient::GetInstance().m_bParticipate;

	if ( !bClientParticipate )
		{
			GLMSG::SNET_TYRANNY_PARTICIPATE NetMsg;
			NetMsg.bParticipate = true;
			NETSEND ( &NetMsg );
	  } else {
			GLMSG::SNET_TYRANNY_PARTICIPATE NetMsg;
			NetMsg.bParticipate = false;
			NETSEND ( &NetMsg );
			}

	return S_OK;
}
HRESULT GLCharacter::ReqSchoolWarRejoin ()
{
	if ( IsACTION(GLAT_ATTACK) || IsACTION(GLAT_SKILL) ||  m_sPMarket.IsOpen() )	return E_FAIL;

	PLANDMANCLIENT pLand = GLGaeaClient::GetInstance().GetActiveMap();
    if ( !pLand )                                 return E_FAIL;

	if ( pLand->m_bTowerWars )
	{
         CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "Rejoin function denied. You are already at the warzone" );
		return E_FAIL;
	}

	GLMSG::SNET_PVP_REJOIN NetMsg;
	NetMsg.bRejoin = true;
	NETSEND ( &NetMsg );

	return S_OK;
}

//	Note : ¿¢¼Ç Äü½½·Ô¿¡ ÀÖ´Â °ÍÀ» ¾²±â. ( ¾àÇ°ÀÏ °æ¿ì ¸¶½Ã±â ).
HRESULT GLCharacter::ReqActionQ ( WORD wSLOT )
{
	HRESULT hr = S_OK;
	if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	if ( EMACTIONQUICK_SIZE <= wSLOT )		return E_FAIL;
	
	const SACTION_SLOT &sACTION = m_sACTIONQUICK[wSLOT];
	if ( !sACTION.VALID() )	return E_FAIL;

	switch ( sACTION.wACT )
	{
	case EMACT_SLOT_DRUG:
		{
			SINVENITEM* pInvenItem = m_cInventory.FindItem ( sACTION.sNID );
			if ( !pInvenItem )						return E_FAIL;

			SITEM* pITEM = GLItemMan::GetInstance().GetItem ( sACTION.sNID );
			if ( !pITEM )							return E_FAIL;

			if ( pITEM->sBasicOp.emItemType != ITEM_CURE && 
				 pITEM->sBasicOp.emItemType != ITEM_TELEPORT_CARD &&		
				 pITEM->sBasicOp.emItemType != ITEM_RECALL /*&& 
				 pITEM->sBasicOp.emItemType != ITEM_PET_CARD*/ ) // Remove Pet On Potion Tray || Eduj :D		
				return E_FAIL;

			hr = ReqInvenDrug ( pInvenItem->wPosX, pInvenItem->wPosY );
			if ( FAILED(hr) )						return hr;
		}
		break;
	};

	return S_OK;
}

//	Note : ½ºÅ³ ¹è¿ì±â ¿äÃ». ( ÀÎº¥ ¾ÆÀÌÅÛÀ¸·Î. )
HRESULT GLCharacter::ReqInvenSkill ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )					return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem || pItem->sBasicOp.emItemType!=ITEM_SKILL )	return E_FAIL;

	SNATIVEID sSKILL_ID = pItem->sSkillBookOp.sSkill_ID;

	if ( ISLEARNED_SKILL(sSKILL_ID) )
	{
		//	ÀÌ¹Ì ½ÀµæÇÑ ½ºÅ³.
		return E_FAIL;
	}

	EMSKILL_LEARNCHECK emSKILL_LEARNCHECK = CHECKLEARNABLE_SKILL(sSKILL_ID);
	if ( emSKILL_LEARNCHECK!=EMSKILL_LEARN_OK )
	{
		//	½ºÅ³ ½Àµæ ¿ä±¸ Á¶°ÇÀ» ÃæÁ·ÇÏÁö ¸øÇÕ´Ï´Ù.
		return E_FAIL;
	}

	//	Note : ½ºÅ³ ½ÀµæÀ» ¿äÃ»ÇÕ´Ï´Ù.
	//
	GLMSG::SNETPC_REQ_LEARNSKILL NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqSkillUp ( const SNATIVEID skill_id )
{
	if ( !IsValidBody() )	return E_FAIL;

	EMSKILL_LEARNCHECK emSKILL_LVLUPCHECK = EMSKILL_LEARN_UNKNOWN;
	SCHARSKILL* pCHARSKILL = GETLEARNED_SKILL(skill_id);
	if ( !pCHARSKILL )
	{
		//	Á¤»óÀûÀ¸·Î ¹ß»ý ÇÒ ¼ö ¾ø´Â »óÈ².
		return E_FAIL;
	}

	emSKILL_LVLUPCHECK = CHECKLEARNABLE_SKILL(skill_id);
	if ( emSKILL_LVLUPCHECK!=EMSKILL_LEARN_OK )
	{
		//	·¦¾÷ Á¶°ÇÀÌ ºÎÁ·ÇÕ´Ï´Ù.
		return E_FAIL;
	}

	GLMSG::SNETPC_REQ_SKILLUP NetMsg;
	NetMsg.skill_id = skill_id;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqReCall ( WORD wPosX, WORD wPosY )
{
	//	ÄÉ¸¯ÀÌ Á¤»ó »óÅÂ°¡ ¾Æ´Ò °æ¿ì.
	//if ( !IsValidBody() )
	//{
	//	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_RECALL_FB_CONDITION") );
	//	return E_FAIL;
	//}
	
	//	°Å·¡ÁßÀÏ °æ¿ì.
	//if ( GLTradeClient::GetInstance().Valid() )
	//{
	//	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_RECALL_FB_CONDITION") );
	//	return E_FAIL;
	//}

	//	´ë·Ã µµÁßÀÏ °æ¿ì.
	if ( m_sCONFTING.IsCONFRONTING() )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_RECALL_FB_CONDITION") );
		return E_FAIL;
	}

	/*if ( m_bAntiPot )
	{
		GLMSG::SNET_ITEM_CANNOT_POT NetMsg;
		GLGaeaServer::GetInstance().SENDTOCLIENT(m_dwClientID,&NetMsg);

		return E_FAIL;
	}*/


	PLANDMANCLIENT pLand = GLGaeaClient::GetInstance().GetActiveMap();
	if ( pLand->IsTowerWars() || pLand->IsRoyalRumble() )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "Usage of this card on this map is disabled." );
		return E_FAIL;
	}

	//	Note : pk µî±ÞÀÌ »ìÀÎÀÚ µî±Þ ÀÌ»óÀÏ °æ¿ì ±ÍÈ¯ Ä«µåÀÇ »ç¿ëÀ» ¸·´Â´Ù.
	//
	DWORD dwPK_LEVEL = GET_PK_LEVEL();
	if ( dwPK_LEVEL != UINT_MAX && dwPK_LEVEL>GLCONST_CHAR::dwPK_RECALL_ENABLE_LEVEL )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_RECALL_FB_PK_LEVEL") );
		return E_FAIL;
	}

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_RECALL_FB_ITEM") );
		return E_FAIL;
	}

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem || pItem->sBasicOp.emItemType!=ITEM_RECALL )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_RECALL_FB_ITEM") );
		return E_FAIL;
	}

	PLANDMANCLIENT pLandManClient = GLGaeaClient::GetInstance().GetActiveMap ();
	if ( pLandManClient && !pLandManClient->IsInstantMap() )
	{
		if ( pLandManClient->m_bTowerWars )
		{
			CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, "Unable to use this item" );
			return E_FAIL;
		}
	}
	if ( pItem->sDrugOp.emDrug!=ITEM_DRUG_CALL_SCHOOL &&
		pItem->sDrugOp.emDrug!=ITEM_DRUG_CALL_REGEN &&
		pItem->sDrugOp.emDrug!=ITEM_DRUG_CALL_LASTCALL )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_RECALL_FB_ITEM") );
		return E_FAIL;
	}

	if( ValidRebuildOpen() )	// ITEMREBUILD_MARK
		CInnerInterface::GetInstance().CloseItemRebuildWindow();

	if( ValidGarbageOpen() )	// ÈÞÁöÅë
		CInnerInterface::GetInstance().CloseItemGarbageWindow();

	if ( ValidItemMixOpen() )
		CInnerInterface::GetInstance().CloseItemMixWindow();



	GLMSG::SNETPC_REQ_INVEN_RECALL NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

// ¼ÒÈ¯¼­ »ç¿ë ¿äÃ» - Æ¯Á¤¸ÊÀ¸·Î ÀÌµ¿
HRESULT GLCharacter::ReqTeleport ( WORD wPosX, WORD wPosY )
{	
	//	´ë·Ã µµÁßÀÏ °æ¿ì.
	if ( m_sCONFTING.IsCONFRONTING() )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_TELEPORT_FB_CONDITION") );
		return E_FAIL;
	}

	//if ( m_bAntiPot )
	//{
	//	GLMSG::SNET_ITEM_CANNOT_POT NetMsg;
	//	GLGaeaServer::GetInstance().SENDTOCLIENT(m_dwClientID,&NetMsg);

	//	return E_FAIL;
	//}

	PLANDMANCLIENT pLand = GLGaeaClient::GetInstance().GetActiveMap();
	WORD CurMapID = GLGaeaClient::GetInstance().GetActiveMapID().wMainID;
	if(pLand->m_bTowerWars || pLand->m_bRoyalRumble /*&& pLand->IsSchoolWar()*/){
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "Usage of teleport card on this map is disabled." );
		return E_FAIL;
	}

	//	Note : pk µî±ÞÀÌ »ìÀÎÀÚ µî±Þ ÀÌ»óÀÏ °æ¿ì ±ÍÈ¯ Ä«µåÀÇ »ç¿ëÀ» ¸·´Â´Ù.
	//
	DWORD dwPK_LEVEL = GET_PK_LEVEL();
	if ( dwPK_LEVEL != UINT_MAX && dwPK_LEVEL>GLCONST_CHAR::dwPK_RECALL_ENABLE_LEVEL )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_TELEPORT_FB_PK_LEVEL") );
		return E_FAIL;
	}

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_TELEPORT_FB_ITEM") );
		return E_FAIL;
	}

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem || pItem->sBasicOp.emItemType!=ITEM_TELEPORT_CARD )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_TELEPORT_FB_ITEM") );
		return E_FAIL;
	}

	if ( pItem->sDrugOp.emDrug!=ITEM_DRUG_CALL_TELEPORT )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_TELEPORT_FB_ITEM") );
		return E_FAIL;
	}

	if ( ValidRebuildOpen() )	// ITEMREBUILD_MARK
		CInnerInterface::GetInstance().CloseItemRebuildWindow();

	if ( ValidGarbageOpen() )	// ÈÞÁöÅë
		CInnerInterface::GetInstance().CloseItemGarbageWindow();

	if ( ValidItemMixOpen() )
		CInnerInterface::GetInstance().CloseItemMixWindow();

	GLMSG::SNETPC_REQ_INVEN_TELEPORT NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;

}

HRESULT GLCharacter::ReqBoxOpen ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )	return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_BOXOPEN_FB_NOITEM") );
		return E_FAIL;
	}

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem || pItem->sBasicOp.emItemType!=ITEM_BOX )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_BOXOPEN_FB_NOBOX") );
		return E_FAIL;
	}

	if ( !pItem->sBox.VALID() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_BOXOPEN_FB_EMPTY") );
		return E_FAIL;
	}

	//	Note : ÀÎº¥ÀÇ ¿©À¯ °ø°£ ÃøÁ¤.
	//
	GLInventory cInvenTemp;
	cInvenTemp.SetAddLine ( m_cInventory.GETAddLine(), true );
	cInvenTemp.Assign ( m_cInventory );

	for ( int i=0; i<ITEM::SBOX::ITEM_SIZE; ++i )
	{
		SITEMCUSTOM sCUSTOM;
		sCUSTOM.sNativeID = pItem->sBox.sITEMS[i].nidITEM;
		if ( sCUSTOM.sNativeID==SNATIVEID(false) )				continue;

		SITEM *pITEM = GLItemMan::GetInstance().GetItem ( sCUSTOM.sNativeID );
		if ( !pITEM )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_BOXOPEN_FB_INVALIDITEM") );
			return E_FAIL;
		}

		BOOL bOK = cInvenTemp.InsertItem ( sCUSTOM );
		if ( !bOK )
		{
			//	Note : ÀÎº¥¿¡ °ø°£ÀÌ ¾ø´Â °ÍÀ¸·Î ÆÇ´ÜµÊ.
			//
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_BOXOPEN_FB_NOTINVEN") );
			return E_FAIL;
		}	
	}

		SITEM* pITEM = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
		if ( !pItem ) return E_FAIL;

		const std::string& strItemName = pITEM->GetName();

	CString strTemp;
			strTemp.Format ( ID2GAMEINTEXT("OPEN_ITEM_BOX"), strItemName.c_str() );
	DoModal ( strTemp, MODAL_QUESTION, YESNO, MODAL_ITEM_BOX_OPEN );

	m_wInvenPosX1 = wPosX;
	m_wInvenPosY1 = wPosY;

	//	Note : ¼­¹ö¿¡ »óÀÚ ¿ÀÇÂÀ» ¿äÃ».
	//
	return S_OK;
}

HRESULT GLCharacter::ReqGMItem ( SNATIVEID sItemID ,WORD wNum ,WORD wPass) //add itemcmd
{

	if ( wPass != GLCONST_CHAR::wGMItemPass )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_GMITEM_PASS") );
		return E_FAIL;
	}

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( sItemID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_GMITEM_NOTITEM") );
		return E_FAIL;
	}

	if ( wNum >= GLCONST_CHAR::wGMItemMax )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_GMITEM_MAX") );
		return E_FAIL;
	}

	GLInventory cInvenTemp;
	cInvenTemp.SetAddLine ( m_cInventory.GETAddLine(), true );
	cInvenTemp.Assign ( m_cInventory );

	for ( int i=0; i< wNum; ++i )
	{
		SITEMCUSTOM sCUSTOM;
		sCUSTOM.sNativeID = pItem->sBasicOp.sNativeID;

		if ( sCUSTOM.sNativeID==SNATIVEID(false) )				continue;

		SITEM *pITEM = GLItemMan::GetInstance().GetItem ( sCUSTOM.sNativeID );
		if ( !pITEM )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_GMITEM_FAIL") );
			return E_FAIL;
		}

		BOOL bOK = cInvenTemp.InsertItem ( sCUSTOM );
		if ( !bOK )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::PALEGREEN, ID2GAMEINTEXT("EMREQ_GMITEM_INFAIL") );
			return E_FAIL;
		}
	}

	GLMSG::SNET_INVEN_GMITEM NetMsg;
	NetMsg.sItemID = sItemID;
	NetMsg.wNum = wNum;
	NetMsg.wPass = wPass;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqRandumBoxOpen ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )	return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_RANDOMBOXOPEN_FB_FAIL") );
		return E_FAIL;
	}

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem || pItem->sBasicOp.emItemType!=ITEM_RANDOMITEM )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_RANDOMBOXOPEN_FB_FAIL") );
		return E_FAIL;
	}

	if ( !pItem->sRandomBox.VALID() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_RANDOMBOXOPEN_FB_EMPTY") );
		return E_FAIL;
	}

	//	Note : ¼­¹ö¿¡ »óÀÚ ¿ÀÇÂÀ» ¿äÃ».
	//
	GLMSG::SNET_INVEN_RANDOMBOXOPEN NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqPetSkinPackOpen ( WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )						return E_FAIL;

	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pHold )	return S_FALSE;

	if ( pHold->sBasicOp.emItemType != ITEM_PET_SKIN_PACK )	return S_FALSE;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_PETSKINPACK_FB_FAIL") );
		return E_FAIL;
	}


	if ( !pHold->sPetSkinPack.VALID() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_PETSKINPACK_FB_EMPTY") );
		return E_FAIL;
	}

	// ÆêÀÌ »ý¼º µÇ¾î ÀÖÁö ¾ÊÀº °æ¿ì
	GLPetClient* pMyPet = GLGaeaClient::GetInstance().GetPetClient ();
	if ( pMyPet == NULL || !pMyPet->IsVALID () )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_PETSKINPACK_FB_PET_FAIL") );
		return E_FAIL;
	}

	if( pInvenItem->sItemCustom.dwPetID != pMyPet->m_dwPetID )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_PETSKINPACK_FB_DIFFER_CARD_FAIL") );
		return E_FAIL;
	}

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem || pItem->sBasicOp.emItemType!=ITEM_PET_CARD )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_PETSKINPACK_FB_PETCARD_FAIL") );
		return E_FAIL;
	}

	if( !CInnerInterface::GetInstance().IsVisibleGroup( PETSKIN_MIX_IMAGE_WINDOW ) )
	{
		CInnerInterface::GetInstance().GetPetSkinMixImage()->SetItemData( wPosX, wPosY, pHold->sBasicOp.sNativeID );
		CInnerInterface::GetInstance().ShowGroupFocus( PETSKIN_MIX_IMAGE_WINDOW );
	}

	
	//	Note : ¼­¹ö¿¡ »óÀÚ ¿ÀÇÂÀ» ¿äÃ».
	//
	/*GLMSG::SNETPET_SKINPACKOPEN NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );*/
	return S_OK;
}

HRESULT GLCharacter::ReqDisJunction ( WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )	return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_DISJUNCTION_FB_FAIL") );
		return E_FAIL;
	}

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pHold )	return S_FALSE;

	if ( pHold->sBasicOp.emItemType!=ITEM_DISJUNCTION )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_DISJUNCTION_FB_FAIL") );
		return S_FALSE;
	}

	if ( pInvenItem->sItemCustom.nidDISGUISE==SNATIVEID(false) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_DISJUNCTION_FB_NONEED") );
		return S_FALSE;
	}

	//	Note : ¼­¹ö¿¡ ÄÚ½ºÅù ºÐ¸® ¿äÃ».
	//
	GLMSG::SNET_INVEN_DISJUNCTION NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//	Note : ¾ÆÀÌÅÛÀ» Âø¿ëÇÏ°Å³ª µé¾î¿Ã¸².
HRESULT GLCharacter::ReqSlotTo ( EMSLOT emSlot )
{
	if ( !VALID_HOLD_ITEM() && !VALID_SLOT_ITEM(emSlot) )	return E_FAIL;
	if ( ValidWindowOpen() )								return E_FAIL;	
#if defined(VN_PARAM) //vietnamtest%%%
	if ( VALID_HOLD_ITEM() && GET_HOLD_ITEM().bVietnamGainItem )	return E_FAIL;
#endif


	//	Note : °ø°ÝÁßÀÌ³ª ½ºÅ³ ½ÃÀü Áß¿¡ ½½·Ô º¯°æÀ» ¼öÇà ÇÒ ¼ö ¾ø´Ù°í º½.
	//
	if ( IsACTION(GLAT_ATTACK) || IsACTION(GLAT_SKILL) )
	{
		return E_FAIL;
	}

	//	SLOT <-> HOLD ( Âø¿ë ¹× ÇØÁ¦ )
	bool bEMPTY_SLOT = !ISEMPTY_SLOT(GET_HOLD_ITEM().sNativeID,emSlot);
	if ( VALID_HOLD_ITEM() && bEMPTY_SLOT )
	{
		BOOL bOk;
		bOk = ACCEPT_ITEM ( GET_HOLD_ITEM().sNativeID );
		if ( !bOk )
		{
			//	Âø¿ëÁ¶°Ç °Ë»ç.
			return E_FAIL;
		}

		bOk = CHECKSLOT_ITEM ( GET_HOLD_ITEM().sNativeID, emSlot );
		if ( !bOk )
		{
			//	ÇØ´ç½½·Ô°ú ¸ÂÁö ¾Ê½À´Ï´Ù.
			return E_FAIL;
		}

		SITEM *pItem = GLItemMan::GetInstance().GetItem(GET_HOLD_ITEM().sNativeID);

		if ( pItem && pItem->sSuitOp.emSuit==SUIT_HANDHELD && pItem->sSuitOp.IsBOTHHAND() )
		{
			EMSLOT emRHand = GetCurRHand();
			EMSLOT emLHand = GetCurLHand();

			SITEM *pItem1=NULL, *pItem2=NULL;
			if ( VALID_SLOT_ITEM(emLHand) && VALID_SLOT_ITEM(emRHand) )
			{
				pItem1 = GLItemMan::GetInstance().GetItem(GET_SLOT_ITEM(emLHand).sNativeID);
				pItem2 = GLItemMan::GetInstance().GetItem(GET_SLOT_ITEM(emRHand).sNativeID);

				WORD wInvenPosX, wInvenPosY;
				bOk = m_cInventory.FindInsrtable ( pItem1->sBasicOp.wInvenSizeX, pItem1->sBasicOp.wInvenSizeY, wInvenPosX, wInvenPosY );
				if ( !bOk )
				{
					//	ÀÎ¹êÅä¸®¿¡ ¿©À¯ °ø°£ÀÌ ºÎÁ·.
					return E_FAIL;
				}
			}
		}

		GLMSG::SNETPC_REQ_SLOT_EX_HOLD NetMsg;
		NetMsg.emSlot = emSlot;
		NETSENDTOFIELD ( &NetMsg );
	}
	//	SLOT -> HOLD ( ÇØÁ¦ )
	else if ( VALID_SLOT_ITEM(emSlot) )
	{
		GLMSG::SNETPC_REQ_SLOT_TO_HOLD NetMsg;
		NetMsg.emSlot = emSlot;
		NETSENDTOFIELD ( &NetMsg );
	}
	//	SLOT <- HOLD ( Âø¿ë )
	else if ( VALID_HOLD_ITEM() )
	{
		BOOL bOk;
		bOk = ACCEPT_ITEM ( GET_HOLD_ITEM().sNativeID );
		if ( !bOk )
		{
			//	Âø¿ëÁ¶°Ç °Ë»ç.
			return E_FAIL;
		}

		bOk = CHECKSLOT_ITEM ( GET_HOLD_ITEM().sNativeID, emSlot );
		if ( !bOk )
		{
			//	ÇØ´ç½½·Ô°ú ¸ÂÁö ¾Ê½À´Ï´Ù.
			return E_FAIL;
		}

		SITEMCUSTOM sCustom = GET_HOLD_ITEM();
		if ( sCustom.IsWrap() ) // wrapper
			return E_FAIL;

		GLMSG::SNETPC_REQ_HOLD_TO_SLOT NetMsg;
		NetMsg.emSlot = emSlot;
		NETSENDTOFIELD ( &NetMsg );		

	}

	return S_OK;
}

HRESULT GLCharacter::ReqSlotChange()
{
	//	Note : ½ºÅ³ ½ÃÀü Áß¿¡ ½½·Ô º¯°æÀ» ¼öÇà ÇÒ ¼ö ¾ø´Ù°í º½.
	//
	if( IsACTION(GLAT_SKILL) )
	{
		return E_FAIL;
	}


	GLMSG::SNETPC_REQ_SLOT_CHANGE NetMsg;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//	Note : ¾ÆÀÌÅÛ ¹Ù´Ú¿¡ ¹ö¸².
HRESULT GLCharacter::ReqHoldToField ( const D3DXVECTOR3 &vPos )
{
	if ( !VALID_HOLD_ITEM () )					return E_FAIL;
	if ( ValidWindowOpen() )					return E_FAIL;	

	const SITEMCUSTOM& sHoldItem = GET_HOLD_ITEM();
	SITEMCUSTOM sCustom = GET_HOLD_ITEM();
	SITEM* pItem = GLItemMan::GetInstance().GetItem ( sHoldItem.sNativeID );
	if ( !pItem )				return E_FAIL;
	
	/*PLANDMANCLIENT pLand = GLGaeaClient::GetInstance().GetActiveMap();
	if ( !pLand )                                 return E_FAIL;
	if ( pLand->m_bRoyalRumble)
	{
         CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "You are inside the War Zone Action Disabled." );
		return E_FAIL;
	}*/
	//	°Å·¡¿É¼Ç
	if ( !pItem->sBasicOp.IsTHROW() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("CANNOT_DROP") );
		return E_FAIL;
	}

	/*if ( pItem->sBasicOp.IsTHROW() )
	{
		CString strTemp;
		strTemp.Format ( ID2GAMEINTEXT("DROP_ITEM"));
		DoModal ( strTemp, MODAL_QUESTION, YESNO, MODAL_DROP_ITEM );
		//CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("CANNOT_DROP") );
		//return S_OK;
	}*/

	if (  !pItem->sBasicOp.IsTHROW()  &&  ( pItem && sCustom.bWrap!=true )   )
    {
    CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "The item you wish to drop is Undropable." );
	return E_FAIL;
    }
	if (  ( pItem && sCustom.bNonDrop !=false )   &&  ( pItem && sCustom.bWrap!=true )   )
    {
    CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "The item you wish to drop is Undropable." );
	return E_FAIL;
    }
	// ÆÖÄ«µåÀÏ°æ¿ì
	if ( pItem->sBasicOp.emItemType == ITEM_PET_CARD )
	{
		GLPetClient* pMyPet = GLGaeaClient::GetInstance().GetPetClient ();
		if ( pMyPet->IsVALID () && sHoldItem.dwPetID == pMyPet->m_dwPetID )
		{
			return E_FAIL;
		}
	}	

	GLMSG::SNETPC_REQ_HOLD_TO_FIELD NetMsg;
	NetMsg.vPos = vPos;
#if defined(VN_PARAM) //vietnamtest%%%
	if ( sHoldItem.bVietnamGainItem )
	{
		NetMsg.bVietnamItem = sHoldItem.bVietnamGainItem;
	}
#endif

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqHoldToFieldFireCracker ( const D3DXVECTOR3 &vPos )
{
	if ( !VALID_HOLD_ITEM () )					return E_FAIL;
	if ( ValidWindowOpen() )					return E_FAIL;	

	SITEM* pITEM = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pITEM )							return E_FAIL;

	if ( pITEM->sBasicOp.emItemType!=ITEM_FIRECRACKER )		return E_FAIL;
	if ( pITEM->sBasicOp.strTargetEffect.empty() )			return E_FAIL;

	GLMSG::SNETPC_REQ_FIRECRACKER NetMsg;
	NetMsg.vPOS = vPos;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

bool GLCharacter::IsNpcPileItem ( SNATIVEID sNID_NPC, DWORD dwChannel, WORD wPosX, WORD wPosY )
{
	if ( ValidWindowOpen() )					return false;	

	PCROWDATA pCrowData = GLCrowDataMan::GetInstance().GetCrowData ( sNID_NPC );

	//	»óÀÎ NPC°¡ Á¸Á¦ÇÏÁö ¾Ê½À´Ï´Ù.
	if ( !pCrowData )								return false;

	if ( pCrowData->GetSaleNum() <= dwChannel )		return false;

	GLInventory *pInven = pCrowData->GetSaleInven ( dwChannel );
	if ( !pInven )									return false;

	SINVENITEM* pSaleItem = pInven->FindPosItem ( wPosX, wPosY );
	if ( !pSaleItem )								return false;
		
	SNATIVEID sBUYNID = pSaleItem->sItemCustom.sNativeID;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( sBUYNID );

	return ( pItem->ISPILE() != FALSE );
}

bool GLCharacter::IsRestartPossible()
{
	GLMapList::FIELDMAP MapsList = GLGaeaClient::GetInstance().GetMapList ();
	GLMapList::FIELDMAP_ITER iter = MapsList.find ( GLGaeaClient::GetInstance().GetActiveMap ()->GetMapID ().dwID );
	if ( iter==MapsList.end() )					return FALSE;

	const SMAPNODE *pMapNode = &(*iter).second;

	return !pMapNode->bRestart;
}

/*void GLCharacter::SendHackingMSG ()
{
	GLMSG::SNET_REQ_SERVERTEST NetMsg;
	NETSENDTOFIELD ( &NetMsg );
}

void GLCharacter::SendHackingMSG1 ()
{
}
*/
// *****************************************************
// Desc: ¾ÆÀÌÅÛÀ» ±¸ÀÔÇÏ°Å³ª/ÆÈ¾Æ¹ö¸²
// *****************************************************
HRESULT GLCharacter::ReqNpcTo ( SNATIVEID sNID_NPC, DWORD dwChannel, WORD wPosX, WORD wPosY, WORD wBuyNum )
{
	if ( ValidWindowOpen() )						return E_FAIL;	

	PCROWDATA pCrowData = GLCrowDataMan::GetInstance().GetCrowData ( sNID_NPC );

	if ( !pCrowData )
	{
		//	»óÀÎ NPC°¡ Á¸ÀçÇÏÁö ¾Ê½À´Ï´Ù.
		return E_FAIL;
	}

	//	¾ÆÀÌÅÆÀ» ÆÈ±â ¼öÇà.
	if ( VALID_HOLD_ITEM() )
	{
		SITEM* pItem = GLItemMan::GetInstance().GetItem ( GET_HOLD_ITEM().sNativeID );

		//	°Å·¡¿É¼Ç

		if ( !pItem->sBasicOp.IsSALE() )
		{
			//	ÆÈ±â °¡´ÉÇÏÁö ¾ÊÀ½.
			return E_FAIL;
		}
		// ÆÖÄ«µåÀÏ°æ¿ì
		if ( pItem->sBasicOp.emItemType == ITEM_PET_CARD )
		{
			const SITEMCUSTOM& sHoldItem = GET_HOLD_ITEM();
			GLPetClient* pMyPet = GLGaeaClient::GetInstance().GetPetClient ();
			if ( pMyPet->IsVALID () && sHoldItem.dwPetID == pMyPet->m_dwPetID )
			{
				return E_FAIL;
			}
		}

		//	ÆÈ±â ¸Þ½ÃÁö.
		GLMSG::SNETPC_REQ_SALE_TO_NPC NetMsg;
		NetMsg.sNID = sNID_NPC;
		NetMsg.dwNPCID = m_dwNPCID;
		NETSENDTOFIELD ( &NetMsg );
	}
	//	¾ÆÀÌÅÆ »ç±â ¼öÇà.
	else
	{
		GASSERT(wBuyNum>0&&"¾ÆÀÌÅÛ ±¸ÀÔ ¿äÃ» ¼ö·®Àº 1°³ ÀÌ»óÀÌ¿©¾ß ÇÑ´Ù.");

		if ( pCrowData->GetSaleNum() <= dwChannel )		return E_FAIL;

		GLInventory *pInven = pCrowData->GetSaleInven ( dwChannel );
		if ( !pInven )									return E_FAIL;

		SINVENITEM* pSaleItem = pInven->FindPosItem ( wPosX, wPosY );
		if ( !pSaleItem )
		{
			//	»ì·Á°í ÇÏ´Â ¾ÆÀÌÅÆÀÌ ¾ø½À´Ï´Ù.
			return E_FAIL;
		}
		
		SNATIVEID sBUYNID = pSaleItem->sItemCustom.sNativeID;

		SITEM* pItem = GLItemMan::GetInstance().GetItem ( sBUYNID );

		LONGLONG dwPrice = pCrowData->GetNpcSellPrice(pItem->sBasicOp.sNativeID.dwID);
		if( dwPrice == 0 )
		{
			dwPrice = pItem->sBasicOp.dwBuyPrice;
		}


		if ( m_lnMoney < (LONGLONG)dwPrice*wBuyNum )
		{
			//	µ·ÀÌ ºÎÁ·ÇÕ´Ï´Ù.
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("BUYITEM_NOMONEY") );
			return E_FAIL;
		}

		WORD wINVENX = pItem->sBasicOp.wInvenSizeX;
		WORD wINVENY = pItem->sBasicOp.wInvenSizeY;

		//	Note : ÀÎº¥¿¡ ¿©À¯ °ø°£ÀÌ ÀÖ´ÂÁö °Ë»ç.
		//
		BOOL bITEM_SPACE(FALSE);
		if ( pItem->ISPILE() )
		{
			//	°ãÄ§ ¾ÆÀÌÅÛÀÏ °æ¿ì.
			WORD wPILENUM = pItem->sDrugOp.wPileNum;
			WORD wREQINSRTNUM = ( wBuyNum * pItem->GETAPPLYNUM() );
			bITEM_SPACE = m_cInventory.ValidPileInsrt ( wREQINSRTNUM, sBUYNID, wPILENUM, wINVENX, wINVENY );
		}
		else
		{
			GASSERT(wBuyNum==1&&"°ãÄ§ÀÌ ºÒ°¡´ÉÇÑ ¾ÆÀÌÅÛÀº 1°³¾¿¸¸ ±¸ÀÔ °¡´ÉÇÕ´Ï´Ù.");

			//	ÀÏ¹Ý ¾ÆÀÌÅÛÀÇ °æ¿ì.
			WORD wInsertPosX(0), wInsertPosY(0);
			bITEM_SPACE = m_cInventory.FindInsrtable ( wINVENX, wINVENY, wInsertPosX, wInsertPosY );
		}

		if ( !bITEM_SPACE )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("BUYITEM_NOSPACE") );
			return E_FAIL;
		}

		//	±¸ÀÔ ¿äÃ» ¸Þ½ÃÁö.
		GLMSG::SNETPC_REQ_BUY_FROM_NPC NetMsg;
		NetMsg.sNID = sNID_NPC;
		NetMsg.dwNPCID = m_dwNPCID;
		NetMsg.dwChannel = dwChannel;
		NetMsg.wPosX = pSaleItem->wPosX;
		NetMsg.wPosY = pSaleItem->wPosY;
		NetMsg.wBuyNum = wBuyNum;

		NETSENDTOFIELD ( &NetMsg );
	}

	return S_OK;
}

// *****************************************************
// Desc: ºô¸µ ¾ÆÀÌÅÛ Á¤º¸ DB¿¡¼­ °¡Á®¿À±â
// *****************************************************
HRESULT GLCharacter::ReqItemBankInfo ()
{
	GLMSG::SNET_GET_CHARGEDITEM_FROMDB NetMsg;
	NetMsg.dwCharID = m_dwCharID;
	StringCchCopy ( NetMsg.szUID, USR_ID_LENGTH+1, m_szUID );

	NETSENDTOFIELD ( &NetMsg );

	m_cInvenCharged.DeleteItemAll ();
	m_mapChargedKey.clear();

	return S_OK;
}

HRESULT GLCharacter::ReqChargedItemTo ( WORD wPosX, WORD wPosY )
{
	SINVENITEM *pINVENITEM = m_cInvenCharged.GetItem ( wPosX, wPosY );
	if ( !pINVENITEM )						return E_FAIL;

	SNATIVEID nidPOS(wPosX,wPosY);
	MAPSHOP_KEY_ITER iter = m_mapChargedKey.find ( nidPOS.dwID );
	if ( m_mapChargedKey.end()==iter )		return E_FAIL;

	std::string strPurKey = (*iter).second;
	
	//	Note : ¾ÆÀÌÅÛ °¡Á®¿À±â ¿äÃ».
	//
	GLMSG::SNET_CHARGED_ITEM_GET NetMsg;
	NetMsg.dwID = nidPOS.dwID;
	StringCchCopy ( NetMsg.szPurKey, PURKEY_LENGTH+1, strPurKey.c_str() );
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

void GLCharacter::ReqLevelUp ()
{
	GLMSG::SNETPC_REQ_LEVELUP NetMsg;
	NETSENDTOFIELD ( &NetMsg );
}

void GLCharacter::ReqStatsUp ( SCHARSTATS sStats )
{
	if ( m_wStatsPoint==0 )	return;
	if ( sStats.GetTotal() >  m_wStatsPoint )	return;

	GLMSG::SNETPC_REQ_STATSUP NetMsg;
	NetMsg.sStats = sStats;
	NETSENDTOFIELD ( &NetMsg );
}

void GLCharacter::ReqStatsUpCmd( EMSTATS emStats, DWORD value )
{
    if ( m_wStatsPoint < value || m_wStatsPoint == 0   )
	{
     CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::RED, "Insufficient Stats. Action cancelled");
	return;
	}else{
	switch( emStats )
	{
        case EMPOW: CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::GREENYELLOW, "Adding %d Pow. Success!", value );	break;
		case EMSTR: CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::GREENYELLOW, "Adding %d Vit. Success!", value );	break;
		case EMSPI: CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::GREENYELLOW, "Adding %d Int. Success!", value );	break;
		case EMDEX: CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::GREENYELLOW, "Adding %d Dex. Success!", value );	break;
		case EMSTA: CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::GREENYELLOW, "Adding %d Agi. Success!", value );	break;
		
	}
}
	GLMSG::SNETPC_REQ_STATSUPCMD NetMsg;
	NetMsg.emStats = emStats;
	NetMsg.dwValue = value;
	NETSENDTOFIELD ( &NetMsg );
}
void GLCharacter::ReqDisableSkill( DWORD dwSKILL, SNATIVEID sKILLID )
{
	if ( dwSKILL > SKILLFACT_SIZE ) return;

	PGLSKILL pSkill = GLSkillMan::GetInstance().GetData ( sKILLID.wMainID, sKILLID.wSubID );
	if ( !pSkill ) return;
	if ( pSkill->m_sBASIC.emIMPACT_SIDE == SIDE_ENERMY ) return;

	GLMSG::SNETPC_REQ_DISABLESKILLEFF NetMsg;
	NetMsg.dwSKILL = dwSKILL;
	NETSENDTOFIELD ( &NetMsg );
}

HRESULT GLCharacter::ReqCheckBuff()
{
	if ( !IsValidBody() )						return S_FALSE;

	GLMSG::SNETPC_REQ_CHECKREWARDBUFF NetMsg;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

void GLCharacter::ReqResetRewardBuff()
{
	if ( !IsValidBody() )						return;

	GLMSG::SNETPC_REQ_DISABLEREWARDBUFF NetMsg;
	NETSENDTOFIELD ( &NetMsg );

}
void GLCharacter::ReqSetTyrannyWin(int nSchool)
{
	GLMSG::SNET_CTF_WIN NetMsg;
	NetMsg.nSchool = nSchool;
	NETSEND(&NetMsg);
}
HRESULT GLCharacter::ReqSkillQuickSet ( const SNATIVEID skill_id, const WORD wSLOT )
{
	if ( EMSKILLQUICK_SIZE <= wSLOT )	return E_FAIL;

	//	Note : ¹è¿î ½ºÅ³ÀÌ ¾Æ´Ò °æ¿ì Ãë¼ÒµÊ.
	if ( !ISLEARNED_SKILL(skill_id) )	return E_FAIL;

	GLMSG::SNETPC_REQ_SKILLQUICK_SET NetMsg;
	NetMsg.wSLOT = wSLOT;
	NetMsg.skill_id = skill_id;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqSkillQuickReSet ( const WORD wSLOT )
{
	if ( EMSKILLQUICK_SIZE <= wSLOT )	return E_FAIL;

	//	Note : Å¬¶óÀÌ¾ðÆ®ÀÇ ½½·ÔÀ» ºñ¿öÁÜ.
	//m_sSKILLQUICK[wSLOT] = NATIVEID_NULL();

	GLMSG::SNETPC_REQ_SKILLQUICK_RESET NetMsg;
	NetMsg.wSLOT = wSLOT;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//	Note : ¾ÆÀÌÅÛ ½½·Ô¿¡ µî·Ï/Á¦°Å.
HRESULT GLCharacter::ReqItemQuickSet ( const WORD wSLOT )
{
	if ( EMACTIONQUICK_SIZE <= wSLOT )	return E_FAIL;
	if ( !VALID_HOLD_ITEM() )			return S_FALSE;

	const SITEMCUSTOM& sITEM = GET_HOLD_ITEM ();
	
#if defined(VN_PARAM) //vietnamtest%%%
	if ( sITEM.bVietnamGainItem ) return S_FALSE;
#endif

	SITEM* pITEM = GLItemMan::GetInstance().GetItem ( sITEM.sNativeID );
	if ( !pITEM )						return S_FALSE;

	if ( pITEM->sBasicOp.emItemType != ITEM_CURE &&
		 pITEM->sBasicOp.emItemType != ITEM_RECALL &&
		 pITEM->sBasicOp.emItemType != ITEM_TELEPORT_CARD /*&& 
		 pITEM->sBasicOp.emItemType != ITEM_PET_CARD*/ )
			// Disabled to avoid crash on Field Server
			// 08-14-2013 11:25 PM
			//  
		 return S_FALSE;

	//	Note : Å¬¶óÀÌ¾ðÆ®ÀÇ ½½·Ô¿¡ ¹Ù·Î ³Ö¾îÁÜ.
	//m_sACTIONQUICK[wSLOT].wACT = EMACT_SLOT_DRUG;
	//m_sACTIONQUICK[wSLOT].sNID = sITEM.sNativeID;

	//	Note : ¼­¹ö¿¡ ½½·Ô¿¡ µé¾î°¥ Á¤º¸ Àü¼Û.
	GLMSG::SNETPC_REQ_ACTIONQUICK_SET NetMsg;
	NetMsg.wSLOT = wSLOT;
	NetMsg.wACT = EMACT_SLOT_DRUG;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqItemQuickReSet ( const WORD wSLOT )
{
	if ( EMACTIONQUICK_SIZE <= wSLOT )	return E_FAIL;

	//m_sACTIONQUICK[wSLOT].RESET ();

	GLMSG::SNETPC_REQ_ACTIONQUICK_RESET NetMsg;
	NetMsg.wSLOT = wSLOT;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//	Note : ½ºÅ³ ½½·Ô¿¡ µî·Ï/Á¦°Å.
HRESULT GLCharacter::ReqSkillRunSet ( const WORD wSLOT )
{
	if ( EMSKILLQUICK_SIZE <= wSLOT )	return E_FAIL;

	const SNATIVEID &skill_id = m_sSKILLQUICK[wSLOT];
	if ( skill_id==NATIVEID_NULL() )	return E_FAIL;

	//	Note : ¹è¿î ½ºÅ³ÀÌ ¾Æ´Ò °æ¿ì Ãë¼ÒµÊ.
	if ( !ISLEARNED_SKILL(skill_id) )	return E_FAIL;

	m_sRunSkill = skill_id;

	GLMSG::SNETPC_REQ_SKILLQUICK_ACTIVE NetMsg;
	NetMsg.wSLOT = wSLOT;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqSkillRunReSet ()
{
	m_sRunSkill = NATIVEID_NULL();
	return S_OK;
}

// *****************************************************
// Desc: ¼­¹ö¿¡ Ã¢°í Á¤º¸ ¿äÃ» ( ¶ôÄ¿ °ü¸®ÀÎ )
// *****************************************************
HRESULT GLCharacter::ReqGetStorage ( DWORD dwChannel, DWORD dwNPCID = 0 )
{
	if ( !IsValidBody() )						return E_FAIL;
	if ( IsVALID_STORAGE(dwChannel) )			return S_OK;

	//	Note : ¼­¹ö¿¡ ¿äÃ».
	//
	GLMSG::SNETPC_REQ_GETSTORAGE	NetMsg;
	NetMsg.dwChannel = dwChannel;
	NetMsg.dwNPCID   = m_dwNPCID;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}


// *****************************************************
// Desc: Ã¢°í ¾ÆÀÌÅÛ µé¶§, ³õÀ»¶§, ±³È¯ÇÒ¶§, ÇÕÄ¥¶§.
// *****************************************************
HRESULT GLCharacter::ReqStorageTo ( DWORD dwChannel, WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )							return E_FAIL;
	if ( !IsVALID_STORAGE(dwChannel) )				return S_OK;
	if ( ValidWindowOpen() )						return E_FAIL;	

	SINVENITEM* pInvenItem = m_cStorage[dwChannel].FindPosItem ( wPosX, wPosY );
	if ( !VALID_HOLD_ITEM () && !pInvenItem )		return E_FAIL;


	if ( VALID_HOLD_ITEM() ) 
	{
		SITEM *pITEM = GLItemMan::GetInstance().GetItem ( GET_HOLD_ITEM().sNativeID );
		if ( !pITEM )		return false;
		
		if (GLCONST_CHAR::ENABLE_LOCKER_CONTROL) if ( !pITEM->sBasicOp.IsLocker() && !pITEM->sBasicOp.IsEXCHANGE() ) return FALSE;
		//	°Å·¡¿É¼Ç
		if ( GET_HOLD_ITEM().bWrap!= true )
		{
		  if ( !pITEM->sBasicOp.IsEXCHANGE() )		return FALSE;
		  if ( GET_HOLD_ITEM().bNonDrop!= false )  return FALSE;
	
		}
	}


	bool bKEEP = IsKEEP_STORAGE(dwChannel);

	if ( VALID_HOLD_ITEM () && pInvenItem )
	{
#if defined(VN_PARAM) //vietnamtest%%%
		if ( !GET_HOLD_ITEM().bVietnamGainItem  )
#endif

		{
			if ( !bKEEP )
			{
				//	Note : ¾ÆÀÌÅÛÀ» ³ÖÀ» ¼ö ¾øÀ» °æ¿ì..
				CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("STORAGE_SPAN_END") );
				return E_FAIL;
			}

			GLMSG::SNETPC_REQ_STORAGE_EX_HOLD NetMsg;
			NetMsg.dwChannel = dwChannel;
			NetMsg.dwNPCID = m_dwNPCID;
			NetMsg.wPosX = pInvenItem->wPosX;
			NetMsg.wPosY = pInvenItem->wPosY;
			NETSENDTOFIELD ( &NetMsg );
		}
	}
	else if ( pInvenItem )
	{
		GLMSG::SNETPC_REQ_STORAGE_TO_HOLD NetMsg;
		NetMsg.dwChannel = dwChannel;
		NetMsg.dwNPCID = m_dwNPCID;
		NetMsg.wPosX = pInvenItem->wPosX;
		NetMsg.wPosY = pInvenItem->wPosY;
		NETSENDTOFIELD ( &NetMsg );
	}
	else if ( VALID_HOLD_ITEM () )
	{
#if defined(VN_PARAM) //vietnamtest%%%
		if ( !GET_HOLD_ITEM().bVietnamGainItem  )
#endif

		{

			if ( !bKEEP )
			{
				//	Note : ¾ÆÀÌÅÛÀ» ³ÖÀ» ¼ö ¾øÀ» °æ¿ì..
				CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("STORAGE_SPAN_END") );
				return E_FAIL;
			}

			//	Note : ¸Þ½ÃÁö ¼Û½ÅÀü¿¡ À¯È¿ÇÒÁö¸¦ ¹Ì¸® °Ë»çÇÔ.
			//
			SITEM* pItem = GLItemMan::GetInstance().GetItem ( GET_HOLD_ITEM().sNativeID );
			GASSERT(pItem&&"¾ÆÀÌÅÆ ´ëÀÌÅÍ°¡ Á¸Á¦ÇÏÁö ¾ÊÀ½");

			BOOL bOk = m_cStorage[dwChannel].IsInsertable ( pItem->sBasicOp.wInvenSizeX, pItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
			if ( !bOk )
			{
				//	ÀÎ¹êÀÌ °¡µæÂþÀ½.
				return E_FAIL;
			}

			GLMSG::SNETPC_REQ_HOLD_TO_STORAGE NetMsg;
			NetMsg.dwChannel = dwChannel;
			NetMsg.dwNPCID = m_dwNPCID;
			NetMsg.wPosX = wPosX;
			NetMsg.wPosY = wPosY;

			NETSENDTOFIELD ( &NetMsg );
		}
	}

	return S_OK;
}


// *****************************************************
// Desc: ½ºÅ³ ¹è¿ì±â ¿äÃ». ( Ã¢°í ¾ÆÀÌÅÛÀ¸·Î. )
// *****************************************************
HRESULT GLCharacter::ReqStorageSkill ( DWORD dwChannel, WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )						return E_FAIL;
	if ( !IsVALID_STORAGE(dwChannel) )			return S_OK;
	if ( ValidWindowOpen() )					return E_FAIL;	

	SINVENITEM* pInvenItem = m_cStorage[dwChannel].FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem || pItem->sBasicOp.emItemType!=ITEM_SKILL )	return E_FAIL;

	SNATIVEID sSKILL_ID = pItem->sSkillBookOp.sSkill_ID;

	if ( ISLEARNED_SKILL(sSKILL_ID) )
	{
		//	ÀÌ¹Ì ½ÀµæÇÑ ½ºÅ³.
		return E_FAIL;
	}

	EMSKILL_LEARNCHECK emSKILL_LEARNCHECK = CHECKLEARNABLE_SKILL(sSKILL_ID);
	if ( emSKILL_LEARNCHECK!=EMSKILL_LEARN_OK )
	{
		//	½ºÅ³ ½Àµæ ¿ä±¸ Á¶°ÇÀ» ÃæÁ·ÇÏÁö ¸øÇÕ´Ï´Ù.
		return E_FAIL;
	}

	//	Note : ½ºÅ³ ½ÀµæÀ» ¿äÃ»ÇÕ´Ï´Ù.
	//
	GLMSG::SNETPC_REQ_LEARNSKILL_STORAGE NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NetMsg.dwNPCID = m_dwNPCID;
	
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

// *****************************************************
// Desc: Ã¢°í ¾ÆÀÌÅÛ »ç¿ëÇÒ¶§ ( ¸¶½Ã±â, ½ºÅ³¹è¿ì±â µî ).
// *****************************************************
HRESULT GLCharacter::ReqStorageDrug ( DWORD dwChannel, WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )						return E_FAIL;
	if ( !IsVALID_STORAGE(dwChannel) )			return S_OK;
	if ( ValidWindowOpen() )					return E_FAIL;	

	SINVENITEM* pInvenItem = m_cStorage[dwChannel].FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	if ( CheckCoolTime( pInvenItem->sItemCustom.sNativeID ) ) return E_FAIL;


	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			return E_FAIL;

	// ·¹º§ Á¶°Ç È®ÀÎ
	if ( !SIMPLE_CHECK_ITEM( pItem->sBasicOp.sNativeID ) ) return S_FALSE;

	if ( pItem->sBasicOp.emItemType == ITEM_CURE )
	{
		//	Note : pk µî±ÞÀÌ »ìÀÎ¸¶ µî±Þ ÀÌ»óÀÏ °æ¿ì È¸º¹¾àÀÇ »ç¿ëÀ» ¸·´Â´Ù.
		//
		DWORD dwPK_LEVEL = GET_PK_LEVEL();
		if ( dwPK_LEVEL != UINT_MAX && dwPK_LEVEL>GLCONST_CHAR::dwPK_DRUG_ENABLE_LEVEL )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_DRUG_PK_LEVEL") );
			return E_FAIL;
		}

		if ( m_sCONFTING.IsFIGHTING() && !m_sCONFTING.IsRECOVE() )
		{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("CONFRONT_RECOVE"), 0 );
			return E_FAIL;
		}

		if ( pItem->sDrugOp.emDrug!=ITEM_DRUG_NUNE )
		{
			switch ( pItem->sDrugOp.emDrug )
			{
			case ITEM_DRUG_HP:
				if ( m_sHP.wMax == m_sHP.wNow )		return S_FALSE;
				break;

			case ITEM_DRUG_MP:
				if ( m_sMP.wMax == m_sMP.wNow )		return S_FALSE;
				break;

			case ITEM_DRUG_SP:
				if ( m_sSP.wMax == m_sSP.wNow )		return S_FALSE;
				break;

			case ITEM_DRUG_HP_MP:
				if ( m_sHP.wMax==m_sHP.wNow && m_sMP.wMax==m_sMP.wNow )		return S_FALSE;
				break;
			case ITEM_DRUG_MP_SP:
				if ( m_sMP.wMax==m_sMP.wNow && m_sSP.wMax==m_sSP.wNow )		return S_FALSE;
				break;

			case ITEM_DRUG_HP_MP_SP:
				if ( m_sHP.wMax==m_sHP.wNow && m_sMP.wMax==m_sMP.wNow
					&& m_sSP.wMax==m_sSP.wNow )		return S_FALSE;
				break;

			case ITEM_DRUG_CURE:
				if ( !ISSTATEBLOW() )				return S_FALSE;
				break;

			case ITEM_DRUG_HP_CURE:
				if ( m_sHP.wMax == m_sHP.wNow && !ISSTATEBLOW() )		return S_FALSE;
				break;

			case ITEM_DRUG_HP_MP_SP_CURE:
				if ( m_sHP.wMax==m_sHP.wNow && m_sMP.wMax==m_sMP.wNow
					&& m_sSP.wMax==m_sSP.wNow && !ISSTATEBLOW() )		return S_FALSE;
				break;
			};

			GLMSG::SNETPC_REQ_STORAGEDRUG NetMsg;
			NetMsg.wPosX = wPosX;
			NetMsg.wPosY = wPosY;
			NetMsg.dwNPCID = m_dwNPCID;

			NETSENDTOFIELD ( &NetMsg );
		}
	}
	else if ( pItem->sBasicOp.emItemType ==ITEM_SKILL )
	{
		ReqStorageSkill ( dwChannel, wPosX, wPosY );
	}
	else if ( pItem->sBasicOp.emItemType ==ITEM_PET_SKILL )
	{
		GLGaeaClient::GetInstance().GetPetClient()->ReqLearnSkill ( dwChannel, wPosX, wPosY );
	}
	
	return S_OK;
}

// *****************************************************
// Desc: Ã¢°í µ· ³Ö±â.
// *****************************************************
HRESULT GLCharacter::ReqStorageSaveMoney ( LONGLONG lnMoney )
{
//#if !defined(KR_PARAM) && !defined(KRT_PARAM)
//	if( m_lnMoney%UINT_MAX < lnMoney )			return E_FAIL;
//#endif
	if ( m_lnMoney < lnMoney )					return E_FAIL;
	if ( ValidWindowOpen() )					return E_FAIL;	

	GLMSG::SNETPC_REQ_STORAGE_SAVE_MONEY NetMsg;
	NetMsg.lnMoney = lnMoney;
	NetMsg.dwNPCID = m_dwNPCID;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

// *****************************************************
// Desc: Ã¢°í µ· »©³»±â.
// *****************************************************
HRESULT GLCharacter::ReqStorageDrawMoney ( LONGLONG lnMoney )
{
//#if !defined(KR_PARAM) && !defined(KRT_PARAM)
//	if( m_lnStorageMoney%UINT_MAX < lnMoney )	return E_FAIL;
//#endif
	if ( m_lnStorageMoney < lnMoney )			return E_FAIL;
	if ( ValidWindowOpen() )					return E_FAIL;	

	GLMSG::SNETPC_REQ_STORAGE_DRAW_MONEY NetMsg;
	NetMsg.lnMoney = lnMoney;
	NetMsg.dwNPCID = m_dwNPCID;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//	Note : µ· ¹Ù´Ú¿¡ ¹ö¸².
HRESULT GLCharacter::ReqMoneyToField ( LONGLONG lnMoney )
{
	if ( !GLCONST_CHAR::bMONEY_DROP2FIELD )		return E_FAIL;
	if ( lnMoney == 0 )							return E_FAIL;
//#if !defined(KR_PARAM) && !defined(KRT_PARAM)
//	if ( m_lnMoney%UINT_MAX < lnMoney )			return E_FAIL;
//#endif
	if ( m_lnMoney < lnMoney )					return E_FAIL;
	if ( ValidWindowOpen() )					return E_FAIL;	

	D3DXVECTOR3 vDir(1,0,0);
	BOOL bCollision = FALSE;
	DWORD dwCollisionID;
	D3DXVECTOR3 vCollisionPos(0,0,0);

	srand(GetTickCount());
	float fBaseY = (rand()%32) * (D3DX_PI*2)/32.0f;

	for ( float fRotY = 0.0f; fRotY<D3DX_PI*2; fRotY += 0.2f )
	{
		D3DXMATRIX matRotY;
		D3DXMatrixRotationY ( &matRotY, fRotY+fBaseY );
		D3DXVec3TransformCoord ( &vDir, &vDir, &matRotY );

		D3DXVec3Normalize ( &vDir, &vDir );
		D3DXVECTOR3 vDropPos = GetPosition() + vDir*float(GLCONST_CHAR::wBODYRADIUS+12);

		GLGaeaClient::GetInstance().GetActiveMap()->GetNaviMesh()->IsCollision
		(
			vDropPos + D3DXVECTOR3(0,+5,0),
			vDropPos + D3DXVECTOR3(0,-5,0),
			vCollisionPos,
			dwCollisionID,
			bCollision
		);
	}

	if ( !bCollision )
	{
		GLGaeaClient::GetInstance().GetActiveMap()->GetNaviMesh()->IsCollision
		(
			GetPosition() + D3DXVECTOR3(0,+5,0),
			GetPosition() + D3DXVECTOR3(0,-5,0),
			vCollisionPos,
			dwCollisionID,
			bCollision
		);
	}

	if ( bCollision )
	{
		GLMSG::SNETPC_REQ_MONEY_TO_FIELD NetMsg;
		NetMsg.vPos = vCollisionPos;
		NetMsg.lnMoney = lnMoney;
		NETSENDTOFIELD ( &NetMsg );
	}

	return S_OK;
}

//	Note : °Å·¡ÇÒ ±Ý¾× ³Ö±â.
//HRESULT GLCharacter::ReqTradeMoney ( LONGLONG lnMoney )
//{
//	if ( !GLTradeClient::GetInstance().Valid () )	return E_FAIL;
//
//	//	Note : ¼ÒÁö ±Ý¾×ÀÌ ÃæºÐÇÑÁö °Ë»ç.
//	//
//	if ( lnMoney > m_lnMoney )		return E_FAIL;
//	
//	//	Note : ±³È¯ÇÒ ±Ý¾× ¼³Á¤ MSG.
//	//
//	GLMSG::SNET_TRADE_MONEY NetMsg;
//	NetMsg.lnMoney = lnMoney;
//	NETSENDTOFIELD(&NetMsg);
//
//	return S_OK;
//}

//	Note : °Å·¡ÇÒ ¾ÆÀÌÅÛ ³Ö±â/Á¦°Å/±³È¯.
HRESULT GLCharacter::ReqTradeBoxTo ( WORD wPosX, WORD wPosY )
{
	if ( !GLTradeClient::GetInstance().Valid () )	return E_FAIL;

	SINVENITEM* pTradeItem = GLTradeClient::GetInstance().FindPosItem ( wPosX, wPosY );	//	ÇöÀç À§Ä¡¿¡ Æ®·¡ÀÌµå ¹°Ç°ÀÌ µî·ÏµÇ¾î ÀÖÀ½.
	SINVEN_POS sPreTradeItem = GLTradeClient::GetInstance().GetPreItem();					//	¿¹ºñ µî·Ï ¾ÆÀÌÅÛÀÌ Á¸Á¦.

	//	Note : ÇØ´ç À§Ä¡¿¡ ÀÌ¹Ì µî·ÏµÈ ¾ÆÀÌÅÛÀÌ ÀÖ°í µî·ÏÇÏ°íÀÚ ÇÏ´Â ¾ÆÀÌÅÛÀÌ ÀÖÀ» °æ¿ì.
	//
	if ( sPreTradeItem.VALID() && pTradeItem )
	{
		//	µî·ÏÇÏ°íÀÚ ÇÏ´Â ¾ÆÀÌÅÛ.
		SINVENITEM* pResistItem = m_cInventory.GetItem ( sPreTradeItem.wPosX, sPreTradeItem.wPosY );
		if ( !pResistItem )		return E_FAIL;

		//	ÀÌ¹Ì µî·ÏµÇ¾î ÀÖ´ÂÁö °Ë»ç.
		SINVENITEM* pAlready = GLTradeClient::GetInstance().FindItemBack ( pResistItem->wBackX, pResistItem->wBackY );
		if ( pAlready )
		{
			GLTradeClient::GetInstance().ReSetPreItem();
			return E_FAIL;
		}

		SITEM *pItem = GLItemMan::GetInstance().GetItem ( pResistItem->sItemCustom.sNativeID );
		if ( !pItem )			return E_FAIL;

		//	°Å·¡¿É¼Ç
		if ( !pResistItem->sItemCustom.IsWrap() )
		{
		  if ( !pItem->sBasicOp.IsEXCHANGE() ) return E_FAIL;
		  if ( pResistItem->sItemCustom.IsNonDrop() ) return E_FAIL;
		}

		if ( pItem->sBasicOp.emItemType == ITEM_PET_CARD )
		{
			// µé°íÀÖ´Â ¾ÆÀÌÅÛÀÌ ÆÖÄ«µåÀÌ¸ç ÆÖÀÌ È°¼ºÈ­ µÇ¾î ÀÖÀ¸¸é
			GLPetClient* pMyPet = GLGaeaClient::GetInstance().GetPetClient ();
			if ( pMyPet->IsVALID () && pResistItem->sItemCustom.dwPetID == pMyPet->m_dwPetID )
			{
				return E_FAIL;
			}
		}

		if ( pItem->sBasicOp.emItemType == ITEM_VEHICLE && pResistItem->sItemCustom.dwVehicleID != 0 )
		{
			return E_FAIL;
		}

		//	Note : Á¾Àü ¾ÆÀÌÅÛ°ú »õ·Î¿î ¾ÆÀÌÅÛÀ» ±³È¯ÇÏ¿© À§Ä¡¿¡ µé¾î °¥ ¼ö ÀÖ´ÂÁö °Ë»ç.
		//
		BOOL bOk = GLTradeClient::GetInstance().IsExInsertable ( pItem->sBasicOp.wInvenSizeX, pItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
		if ( !bOk )				return FALSE;

		//	Note : Á¾Àü ¾ÆÀÌÅÛÀ» ¸®½ºÆ®¿¡¼­ Á¦°Å.
		//
		GLMSG::SNET_TRADE_ITEM_REMOVE NetMsgReMove;
		NetMsgReMove.wPosX = pTradeItem->wPosX;
		NetMsgReMove.wPosY = pTradeItem->wPosY;
		NETSENDTOFIELD(&NetMsgReMove);

		//	Note : »õ·Î¿î ¾ÆÀÌÅÛÀ» ¸®½ºÆ®¿¡ µî·Ï.
		GLMSG::SNET_TRADE_ITEM_REGIST NetMsgRegist;
		NetMsgRegist.wPosX = wPosX;
		NetMsgRegist.wPosY = wPosY;
		NetMsgRegist.wInvenX = sPreTradeItem.wPosX;
		NetMsgRegist.wInvenY = sPreTradeItem.wPosY;
		NETSENDTOFIELD(&NetMsgRegist);

		GLTradeClient::GetInstance().ReSetPreItem();
	}
	//	Note : µî·ÏÇÒ ¾ÆÀÌÅÛÀÌ ÀÖ´Â °æ¿ì.
	//
	else if ( sPreTradeItem.VALID() )
	{
		//	µî·ÏÇÏ°íÀÚ ÇÏ´Â ¾ÆÀÌÅÛ.
		SINVENITEM* pResistItem = m_cInventory.GetItem ( sPreTradeItem.wPosX, sPreTradeItem.wPosY );
		if ( !pResistItem )		return E_FAIL;

		//	ÀÌ¹Ì µî·ÏµÇ¾î ÀÖ´ÂÁö °Ë»ç.
		SINVENITEM* pAlready = GLTradeClient::GetInstance().FindItemBack ( sPreTradeItem.wPosX, sPreTradeItem.wPosY );
		if ( pAlready )
		{
			GLTradeClient::GetInstance().ReSetPreItem();
			return E_FAIL;
		}

		//	Note : ÇØ´çÀ§Ä¡¿¡ µé¾î °¥ ¼ö ÀÖ´ÂÁö °Ë»ç.
		//
		SITEM *pItem = GLItemMan::GetInstance().GetItem ( pResistItem->sItemCustom.sNativeID );
		if ( !pItem )			return E_FAIL;

		//	°Å·¡¿É¼Ç
		if ( !pResistItem->sItemCustom.IsWrap() )
		{
		   if ( !pItem->sBasicOp.IsEXCHANGE() ) return E_FAIL;
		   if ( pResistItem->sItemCustom.IsNonDrop()  ) return E_FAIL;
		}

		if ( pItem->sBasicOp.emItemType == ITEM_PET_CARD )
		{
			// µé°íÀÖ´Â ¾ÆÀÌÅÛÀÌ ÆÖÄ«µåÀÌ¸ç ÆÖÀÌ È°¼ºÈ­ µÇ¾î ÀÖÀ¸¸é
			const SITEMCUSTOM& sHold = GET_HOLD_ITEM();
			GLPetClient* pMyPet = GLGaeaClient::GetInstance().GetPetClient ();
			if ( pMyPet->IsVALID () && pResistItem->sItemCustom.dwPetID == pMyPet->m_dwPetID )
			{
				return E_FAIL;
			}
		}

		if ( pItem->sBasicOp.emItemType == ITEM_VEHICLE && pResistItem->sItemCustom.dwVehicleID != 0 )
		{
			return E_FAIL;
		}

		BOOL bOk = GLTradeClient::GetInstance().IsInsertable ( pItem->sBasicOp.wInvenSizeX, pItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
		if ( !bOk )				return E_FAIL;

		//	Note : »õ·Î¿î ¾ÆÀÌÅÛÀ» ¸®½ºÆ®¿¡ µî·Ï.
		GLMSG::SNET_TRADE_ITEM_REGIST NetMsgRegist;
		NetMsgRegist.wPosX = wPosX;
		NetMsgRegist.wPosY = wPosY;
		NetMsgRegist.wInvenX = sPreTradeItem.wPosX;
		NetMsgRegist.wInvenY = sPreTradeItem.wPosY;
		NETSENDTOFIELD(&NetMsgRegist);

		GLTradeClient::GetInstance().ReSetPreItem();
	}
	//	Note : ¸®½ºÆ®¿¡¼­ »èÁ¦ÇÒ ¾ÆÀÌÅÛÀÌ ÀÖ´Â°æ¿ì.
	//
	else if ( pTradeItem )
	{
		GLMSG::SNET_TRADE_ITEM_REMOVE NetMsgReMove;
		NetMsgReMove.wPosX = pTradeItem->wPosX;
		NetMsgReMove.wPosY = pTradeItem->wPosY;
		NETSENDTOFIELD(&NetMsgReMove);
	}
	
	return S_OK;
}

//	Note : °Å·¡ ¼ö¶ô.
HRESULT GLCharacter::ReqTradeAgree ()
{
	if ( !GLTradeClient::GetInstance().Valid () )	return E_FAIL;

	if ( !GLTradeClient::GetInstance().IsAgreeAble() )
	{
		//	Note : Áö±ÝÀº °Å·¡ ½ÂÀÎÀ» ÇÒ ¼ö ¾ø½À´Ï´Ù. X`s ÈÄ¾Ö ´Ù½Ã ½Ãµµ ÇÏ¿© ÁÖ½Ê½Ã¿ä.
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("TRADE_AGREE_TIME") );
		return E_FAIL;
	}

	//	Note : °Å·¡½Ã µé¾î¿Ã ¾ÆÀÌÅÛµéÀÇ °ø°£ÀÇ ¿©À¯°¡ ÀÖ´ÂÁö °Ë»ç.
	//
	if ( !IsVaildTradeInvenSpace () )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("TRADE_AGREE_FAIL_MYINVEN") );
		return E_FAIL;
	}

	GLMSG::SNET_TRADE_AGREE NetMsg;
	NETSENDTOFIELD(&NetMsg);
	
	return S_OK;
}

//	Note : °Å·¡ Ãë¼Ò.
HRESULT GLCharacter::ReqTradeCancel ()
{
	if ( !GLTradeClient::GetInstance().Valid () )	return E_FAIL;
	
	GLMSG::SNET_TRADE_CANCEL NetMsg;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

//	Note : ºÎÈ° À§Ä¡ ÁöÁ¤ ¿äÃ».
HRESULT GLCharacter::ReqReGenGate ( DWORD dwNpcID )
{
	GLMSG::SNETPC_REQ_REGEN_GATE NetMsg;

	PLANDMANCLIENT pLandMan = GLGaeaClient::GetInstance().GetActiveMap ();
	PGLCROWCLIENT pCrow = pLandMan->GetCrow ( dwNpcID );
	if ( !pCrow )										goto _REQ_FAIL;
	if ( pCrow->GETCROW() != CROW_NPC )					goto _REQ_FAIL;

	//	Note : ºÎÈ°À§Ä¡ ÁöÁ¤ ¿äÃ».
	//
	NetMsg.dwNpcID = dwNpcID;
	NETSEND(&NetMsg);

	return S_OK;

_REQ_FAIL:
	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREGEN_GATE_FAIL") );
	return E_FAIL;
}

// *****************************************************
// Desc: ºÎÈ°ÇÏ±â
// *****************************************************
void GLCharacter::ReqReBirth ()
{
	if ( !IsACTION(GLAT_DIE) )			return;

	//	´ë±â»óÅÂ¿¡ µé¾î°¨.
	m_dwWAIT = 0;
	SetSTATE(EM_ACT_DIE);

	//	ºÎÈ° ¿äÃ».
	GLMSG::SNETPC_REQ_REBIRTH NetMsg;
	NetMsg.bRegenEntryFailed = FALSE;
	NetMsg.bCTFMAP = FALSE;

	//  ºÎÈ° ÁöÁ¡À¸·Î ÁøÀÔ ºÒ°¡½Ã ºÎÈ° ÁöÁ¡À» ÃÊ±âÈ­ÇÑ´Ù.
	if ( m_dwUserLvl < USER_GM3 )	
	{
		SMAPNODE *pNODE = GLGaeaClient::GetInstance().FindMapNode(m_sStartMapID);
		if ( pNODE )
		{
			EMREQFAIL emReqFail(EMREQUIRE_COMPLETE);
			GLLevelFile cLevelFile;
			BOOL bOk = cLevelFile.LoadFile ( pNODE->strFile.c_str(), TRUE, NULL );
			if( bOk )
			{
				SLEVEL_REQUIRE* pRequire = cLevelFile.GetLevelRequire ();
				emReqFail = pRequire->ISCOMPLETE ( this );

				if ( emReqFail != EMREQUIRE_COMPLETE )
				{
					m_sStartMapID = GLCONST_CHAR::nidSTARTMAP[m_wSchool];
					m_dwStartGate = GLCONST_CHAR::dwSTARTGATE[m_wSchool];
					m_vStartPos   = D3DXVECTOR3(0.0f,0.0f,0.0f);
					NetMsg.bRegenEntryFailed = TRUE;
				}
			}
		}
	}


	PLANDMANCLIENT pLAND = GLGaeaClient::GetInstance().GetActiveMap ();
	if ( pLAND && ( pLAND->m_bTowerWars || pLAND->m_bRoyalRumble ))
	{
		NetMsg.bCTFMAP = TRUE;
		NetMsg.sCURMAPID = pLAND->GetMapID();
	}

	NETSEND ( &NetMsg );

	// ¹èÆ²·Î¾â ÀÌº¥Æ®ÀÏ¶§ ¾²·¯Áö¸é Åõ¸í¸ðµå ÇØÁ¦
	if ( GLCONST_CHAR::bBATTLEROYAL )
	{
		ReSetSTATE(EM_REQ_VISIBLEOFF);

		//	Note : (¿¡ÀÌÁ¯Æ®¼­¹ö) ¸Þ¼¼Áö ¹ß»ý.
		//
		GLMSG::SNETPC_ACTSTATE NetMsg;
		NetMsg.dwActState = m_dwActState;
		NETSEND ( (NET_MSG_GENERIC*) &NetMsg );
	}
}

// *****************************************************
// Desc: ºÎÈ°ÇÏ±â (±ÍÈ¥ÁÖ »ç¿ë)
// *****************************************************
HRESULT GLCharacter::ReqReGenRevive ()
{
	if ( !IsACTION(GLAT_DIE) )			return E_FAIL;
	if ( !ISREVIVE () )					return E_FAIL;

	//	´ë±â»óÅÂ¿¡ µé¾î°¨.
	m_dwWAIT = 0;
	SetSTATE(EM_ACT_DIE);

	// ±ÍÈ¥ÁÖ »ç¿ë ¿äÃ»
	GLMSG::SNETPC_REQ_REVIVE NetMsg;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

// °æÇèÄ¡ º¹±¸¸¦ ¿äÃ»ÇÑ´Ù.
HRESULT GLCharacter::ReqRecoveryExp ()
{
// °æÇèÄ¡È¸º¹_Á¤ÀÇ_Normal
#if defined( _RELEASED ) || defined ( KRT_PARAM ) || defined ( KR_PARAM ) 
	if ( !IsACTION(GLAT_DIE) )			return E_FAIL;

	//	´ë±â»óÅÂ¿¡ µé¾î°¨.
	m_dwWAIT = 0;
	SetSTATE(EM_ACT_DIE);

	// °æÇèÄ¡È¸º¹ ºÎÈ° ¿äÃ»
	GLMSG::SNETPC_REQ_RECOVERY NetMsg;
	NETSENDTOFIELD(&NetMsg);
#endif
	
	return S_OK;
}

// °æÇèÄ¡ º¹±¸¸¦ ¿äÃ»ÇÑ´Ù Npc
HRESULT GLCharacter::ReqRecoveryExpNpc ( DWORD dwNpcID )
{
// °æÇèÄ¡È¸º¹_Á¤ÀÇ_Npc
#if defined( _RELEASED ) || defined ( KRT_PARAM ) || defined ( KR_PARAM ) || defined ( TH_PARAM ) || defined ( MYE_PARAM ) || defined ( MY_PARAM ) || defined ( PH_PARAM ) || defined ( CH_PARAM ) || defined ( TW_PARAM ) || defined ( HK_PARAM ) || defined ( GS_PARAM )
	GLMSG::SNETPC_REQ_RECOVERY_NPC NetMsg;
	NetMsg.dwNPCID = dwNpcID;
	NETSENDTOFIELD(&NetMsg);
#endif
	return S_OK;
}


//  Note : º¹±¸ÇÒ °æÇèÄ¡¸¦ ¹¯´Â´Ù.
HRESULT GLCharacter::ReqGetReExp ()
{
// °æÇèÄ¡È¸º¹_Á¤ÀÇ_Normal
#if defined( _RELEASED ) || defined ( KRT_PARAM ) || defined ( KR_PARAM )

	if ( !IsACTION(GLAT_DIE) )			return E_FAIL;	

	// º¹±¸ÇÒ °æÇèÄ¡¸¦ ¿äÃ»
	GLMSG::SNETPC_REQ_GETEXP_RECOVERY NetMsg;
	NETSENDTOFIELD(&NetMsg);
#endif

	return S_OK;
}

HRESULT GLCharacter::ReqGetReExpNpc ( DWORD dwNpcID )
{
// °æÇèÄ¡È¸º¹_Á¤ÀÇ_Npc
#if defined( _RELEASED ) || defined ( KRT_PARAM ) || defined ( KR_PARAM ) || defined ( TH_PARAM ) || defined ( MYE_PARAM ) || defined ( MY_PARAM ) || defined ( PH_PARAM ) || defined ( CH_PARAM ) || defined ( TW_PARAM ) || defined ( HK_PARAM ) || defined ( GS_PARAM )
	// º¹±¸ÇÒ °æÇèÄ¡¸¦ ¿äÃ»
	GLMSG::SNETPC_REQ_GETEXP_RECOVERY_NPC NetMsg;
	NetMsg.dwNPCID = dwNpcID;
	NETSENDTOFIELD(&NetMsg);
#endif

	return S_OK;
}


HRESULT GLCharacter::ReqCure ( DWORD dwNpcID, DWORD dwGlobalID )
{
	GLMSG::SNETPC_REQ_CURE NetMsg;

	PLANDMANCLIENT pLandMan = GLGaeaClient::GetInstance().GetActiveMap ();
	PGLCROWCLIENT pCrow = pLandMan->GetCrow ( dwNpcID );

	if ( !pCrow )										goto _REQ_FAIL;
	if ( pCrow->GETCROW() != CROW_NPC )					goto _REQ_FAIL;

	//	Note : Ä¡·á ¿äÃ».
	NetMsg.dwNpcID = dwNpcID;
	NetMsg.dwGlobalID = dwGlobalID;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;

_REQ_FAIL:
	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREGEN_CURE_FAIL") );

	return S_OK;
}

//	Note : Ä³¸¯ÅÍ Á¤º¸ ¸®¼Â. ( stats, skill )
//
HRESULT GLCharacter::ReqCharReset ( DWORD dwNpcID )
{
	EMREGEN_CHARRESET_FB emFB = EMREGEN_CHARRESET_FAIL;
	WORD wPosX(0), wPosY(0);
	GLMSG::SNETPC_REQ_CHARRESET NetMsg;

	PLANDMANCLIENT pLandMan = GLGaeaClient::GetInstance().GetActiveMap ();
	PGLCROWCLIENT pCrow = pLandMan->GetCrow ( dwNpcID );

	if ( !pCrow )										goto _REQ_FAIL;
	if ( pCrow->GETCROW() != CROW_NPC )					goto _REQ_FAIL;


	bool bITEM = m_cInventory.GetCharResetItem ( wPosX, wPosY );
	if ( !bITEM )
	{
		emFB = EMREGEN_CHARRESET_ITEM_FAIL;
		goto _REQ_FAIL;
	}

	//	Note : Ä³¸¯ÅÍ Á¤º¸ ¸®¼Â.
	NetMsg.dwNpcID = dwNpcID;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;

_REQ_FAIL:
	
	switch ( emFB )
	{
	case EMREGEN_CHARRESET_FAIL:
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREGEN_CHARRESET_FAIL") );
		break;

	case EMREGEN_CHARRESET_ITEM_FAIL:
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREGEN_CHARRESET_ITEM_FAIL") );
		break;
	};

	return S_OK;
}

//	Note : NPC¿Í itemÀ» ±³È¯ A:npc¿¡°Ô ÁÖ´Â°Íµé, b:npc¿¡°Ô¼­ ¹Þ´Â°Í.
HRESULT GLCharacter::ReqItemTrade ( DWORD dwNpcID, DWORD dwGlobalID, DWORD *pDwA_NID, DWORD dwB_NID )
{
	EMNPC_ITEM_TRADE_FB emFB = EMNPC_ITEM_TRADE_FAIL;
	WORD wPosX(0), wPosY(0);
	GLMSG::SNETPC_REQ_NPC_ITEM_TRADE NetMsg;

	PLANDMANCLIENT pLandMan = GLGaeaClient::GetInstance().GetActiveMap ();
	PGLCROWCLIENT pCrow = pLandMan->GetCrow ( dwNpcID );

	if ( !pCrow )										goto _REQ_FAIL;
	if ( pCrow->GETCROW() != CROW_NPC )					goto _REQ_FAIL;

	// Need Add New Item Con.
	BOOL bOK[] = { FALSE, FALSE, FALSE, FALSE, FALSE };
	bOK[0] = ISHAVEITEM ( SNATIVEID(pDwA_NID[0]) );
	BYTE i;
	// MAX_NEEDITEM_COUNT 5
	for( i = 1; i < 5; i++ )
	{
		if( pDwA_NID[i] == UINT_MAX )
			bOK[i] = TRUE;
		else
			bOK[i] = ISHAVEITEM ( SNATIVEID(pDwA_NID[i]) );	
	}

	if( bOK[0] == FALSE || bOK[1] == FALSE || bOK[2] == FALSE || bOK[3] == FALSE || bOK[4] == FALSE )
	{
		emFB = EMNPC_ITEM_TRADE_ITEM_FAIL;
		goto _REQ_FAIL;
	}

	//	Note : ¾ÆÀÌÅÛ ±³È¯ ¿äÃ».
	//
	NetMsg.dwNpcID    = dwNpcID;
	// MAX_NEEDITEM_COUNT 5
	for( i = 0; i < 5; i++ )
	{
		NetMsg.dwA_NID[i]    = pDwA_NID[i];
	}
	NetMsg.dwB_NID    = dwB_NID;
	NetMsg.dwGlobalID = dwGlobalID;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;

_REQ_FAIL:
	switch ( emFB )
	{
	case EMNPC_ITEM_TRADE_FAIL:
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMNPC_ITEM_TRADE_FAIL") );
		break;

	case EMNPC_ITEM_TRADE_ITEM_FAIL:
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMNPC_ITEM_TRADE_ITEM_FAIL") );
		break;
	};

	return S_OK;
}

// *****************************************************
// Desc: Ä£±¸¿¡°Ô ÀÌµ¿(Ä£±¸ÀÌµ¿Ä«µå)
// *****************************************************
HRESULT	GLCharacter::Req2Friend ( const char *szNAME )
{
	SFRIEND* pFRIEND = GLFriendClient::GetInstance().GetFriend ( szNAME );
	if ( !pFRIEND )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EM2FRIEND_FB_FAIL") );
		return S_FALSE;
	}

	PLANDMANCLIENT pLAND = GLGaeaClient::GetInstance().GetActiveMap();
	if ( pLAND->m_bTowerWars )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "Disable" );
		return E_FAIL;
	}
	SINVENITEM *pINVENITEM = m_cInventory.FindItem ( ITEM_2FRIEND );
	if ( !pINVENITEM )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EM2FRIEND_FB_NO_ITEM") );
		return S_FALSE;
	}

	//	Note : Ä£±¸¿¡°Ô °¡±â ¿äÃ».
	GLMSG::SNETPC_2_FRIEND_REQ NetMsg;
	StringCchCopy ( NetMsg.szFRIEND_NAME, CHAR_SZNAME, pFRIEND->szCharName );
	NetMsg.wItemPosX = pINVENITEM->wPosX;
	NetMsg.wItemPosY = pINVENITEM->wPosY;
	NETSEND ( &NetMsg);

	return S_OK;
}

bool GLCharacter::IsInvenSplitItem ( WORD wPosX, WORD wPosY, bool bVietnamInven )
{
	SINVENITEM* pInvenItem = NULL;
	if( bVietnamInven )
	{
		pInvenItem = m_cVietnamInventory.FindPosItem ( wPosX, wPosY );
	}else{
		pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	}
	if ( !pInvenItem )	return false;

	//	Note : ¾ÆÀÌÅÛ Á¤º¸ °¡Á®¿À±â.
	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	GASSERT(pItem&&"¾ÆÀÌÅÆ ´ëÀÌÅÍ°¡ Á¸Á¦ÇÏÁö ¾ÊÀ½");

	bool bSPLIT(false);
	bSPLIT = ( pItem->ISINSTANCE() );
	if ( !bSPLIT )					return FALSE;
	bSPLIT = ( pInvenItem->sItemCustom.wTurnNum>1 );

	return bSPLIT;
}

bool GLCharacter::IsStorageSplitItem ( DWORD dwChannel, WORD wPosX, WORD wPosY )
{
	GASSERT(EMSTORAGE_CHANNEL>dwChannel);
	if ( EMSTORAGE_CHANNEL<=dwChannel )	return false;

	SINVENITEM* pInvenItem = m_cStorage[dwChannel].FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return false;

	//	Note : ¾ÆÀÌÅÛ Á¤º¸ °¡Á®¿À±â.
	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	GASSERT(pItem&&"¾ÆÀÌÅÆ ´ëÀÌÅÍ°¡ Á¸Á¦ÇÏÁö ¾ÊÀ½");

	bool bSPLIT(false);
	bSPLIT = ( pItem->ISPILE() );
	if ( !bSPLIT )					return FALSE;
	bSPLIT = ( pInvenItem->sItemCustom.wTurnNum>1 );

	return bSPLIT;
}

//	Note : ÀÎº¥Åä¸® - °ãÄ§ ¾ÆÀÌÅÛ ºÐ¸®.
HRESULT GLCharacter::ReqInvenSplit ( WORD wPosX, WORD wPosY, WORD wSplitNum )
{
	if ( !IsInvenSplitItem(wPosX,wPosY) )	return E_FAIL;
	
	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )									return E_FAIL;

	if ( pInvenItem->sItemCustom.wTurnNum <= wSplitNum )
	{
		return E_FAIL;
	}

	//	Note : ¾ÆÀÌÅÛ ºÐ¸® ¸Þ½ÃÁö Àü¼Û.
	GLMSG::SNETPC_REQ_INVEN_SPLIT NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NetMsg.wSplit = wSplitNum;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

// *****************************************************
// Desc: Ã¢°í - °ãÄ§ ¾ÆÀÌÅÛ ºÐ¸®.
// *****************************************************
HRESULT GLCharacter::ReqStorageSplit ( DWORD dwChannel, WORD wPosX, WORD wPosY, WORD wSplitNum )
{
	if ( !IsStorageSplitItem(dwChannel,wPosX,wPosY) )	return E_FAIL;

	SINVENITEM* pInvenItem = m_cStorage[dwChannel].FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return false;
	
	if ( pInvenItem->sItemCustom.wTurnNum <= wSplitNum )
	{
		return E_FAIL;
	}

	//	Note : ¾ÆÀÌÅÛ ºÐ¸® ¸Þ½ÃÁö Àü¼Û.
	GLMSG::SNETPC_REQ_STORAGE_SPLIT NetMsg;
	NetMsg.dwChannel = dwChannel;
	NetMsg.dwNPCID = m_dwNPCID;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NetMsg.wSplit = wSplitNum;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

//	Note : ´ë·Ã ¿äÃ».
//
HRESULT GLCharacter::ReqConflict ( DWORD dwID, const SCONFT_OPTION &sOption )
{
	if ( !IsValidBody() )					return E_FAIL;

	PGLCHARCLIENT pChar = GLGaeaClient::GetInstance().GetChar ( dwID );
	if ( !pChar )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMCONFRONT_FAIL") );
		return E_FAIL;
	}

	PLANDMANCLIENT pLAND = GLGaeaClient::GetInstance().GetActiveMap();
	if ( pLAND->m_bTowerWars )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "Disable" );
		return E_FAIL;
	}
	if ( GLGaeaClient::GetInstance().GetActiveMap()->IsPeaceZone() )
	{
		//	Note : ÆòÈ­ Áö¿ª¿¡¼­´Â ´ë·ÃÀÌ ºÒ°¡´ÉÇÕ´Ï´Ù.
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMCONFRONT_PEACE") );
		return E_FAIL;
	}

	if ( !sOption.VALID_OPT() )	
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMCONFRONT_OPTION") );
		return E_FAIL;
	}

	if ( m_sCONFTING.IsCONFRONTING() )
	{
		//	Note : ÀÌ¹Ì ´ë·ÃÁßÀÌ¿©¼­ ´ë·Ã ½ÅÃ»ÀÌ ºÒ°¡´ÉÇÕ´Ï´Ù.
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMCONFRONT_ALREADY_ME") );
		return E_FAIL;
	}

	GLPARTY_CLIENT* pParty = GLPartyClient::GetInstance().FindMember ( dwID );
	if ( pParty )
	{
		//	Note : °°Àº ¼Ò¼ÓÀÇ ÆÄÆ¼¿øÀÌ¿©¼­ ´ë·Ã ºÒ°¡´ÉÇÕ´Ï´Ù.
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMCONFRONT_PARTY") );
		return E_FAIL;
	}

	//	Note : Å¬·´ ´ë·ÃÀÏ °æ¿ì.
	if ( IsClubMaster() && pChar->IsClubMaster() )
	{
		GLMSG::SNETPC_REQ_CONFRONT NetMsg;
		NetMsg.emTYPE = EMCONFT_GUILD;
		NetMsg.dwID = dwID;
		NetMsg.sOption = sOption;
		NETSEND(&NetMsg);
	}
	//	Note : ÆÄÆ¼ ´ë·ÃÀÏ °æ¿ì.
	else 
	if ( IsPartyMaster() && pChar->IsPartyMaster() )
	{
		GLMSG::SNETPC_REQ_CONFRONT NetMsg;
		NetMsg.emTYPE = EMCONFT_PARTY;
		NetMsg.dwID = dwID;
		NetMsg.sOption = sOption;
		NETSEND(&NetMsg);
	}
	//	Note : °³ÀÎ ´ë·ÃÀÏ °æ¿ì.
	else
	{
		if ( IsPartyMem() )
		{
			//	Note : ÆÄÆ¼Àå¸¸ 'ÆÄÆ¼´ë·Ã'À» ½ÅÃ»ÇÒ ¼ö ÀÖ½À´Ï´Ù.
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMCONFRONT_MENOTMASTER") );
			return E_FAIL;
		}

		GLMSG::SNETPC_REQ_CONFRONT NetMsg;
		NetMsg.emTYPE = EMCONFT_ONE;
		NetMsg.dwID = dwID;
		NetMsg.sOption = sOption;
		NETSEND(&NetMsg);
	}

	return S_OK;
}

void GLCharacter::ReqSkillReaction ( STARGETID sTID )
{
	if ( IsACTION(GLAT_SKILL) || IsACTION(GLAT_ATTACK) )	return;

	BOOL bMove(FALSE);
	D3DXVECTOR3 vMoveTo;

	m_sActiveSkill = m_sRunSkill;

	PGLSKILL pRunSkill = GLSkillMan::GetInstance().GetData ( m_sActiveSkill );
	if ( !pRunSkill )										return;
	if ( pRunSkill->m_sBASIC.emIMPACT_SIDE == SIDE_ENERMY )	return;

	SetDefenseSkill( false );

	GLCOPY* pCOPY = GLGaeaClient::GetInstance().GetCopyActor(sTID);
	if ( !pCOPY )	return;

	sTID.vPos = pCOPY->GetPosition();
	SkillReaction ( sTID, DXKEY_UP, false, bMove, vMoveTo );

	//	Note : Reaction ¿¡¼­ ÀÌµ¿À» ¿äÃ»ÇÑ °æ¿ì.
	//
	if ( bMove )
	{
		ActionMoveTo ( 0.0f, vMoveTo+D3DXVECTOR3(0,+5,0), vMoveTo+D3DXVECTOR3(0,-5,0), FALSE, TRUE );
	}
}

HRESULT GLCharacter::ReqQuestStart ( DWORD dwNpcID, DWORD dwTalkID, DWORD dwQUESTID )
{
	//	Note : Äù½ºÆ® ½ÃÀÛÀ» À§ÇÑ °Ë»ç.
	//
	GLQUEST* pQUEST = GLQuestMan::GetInstance().Find ( dwQUESTID );
	if ( !pQUEST )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMQUEST_START_FB_FAIL") );
		return S_FALSE;
	}

	//	ÀÌ¹Ì ÁøÇàÁßÀÎ Äù½ºÆ® ÀÎÁö Á¡°Ë.
	GLQUESTPROG* pQUEST_PROG = m_cQuestPlay.FindProc ( dwQUESTID );
	if ( pQUEST_PROG )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMQUEST_START_FB_ALREADY") );
		return S_FALSE;
	}

	//	ÀÌ¹Ì ¿Ï·áÇÑ Äù½ºÆ®ÀÎÁö °Ë»ç.
	GLQUESTPROG* pQUEST_END = m_cQuestPlay.FindEnd ( dwQUESTID );
	if ( pQUEST_END )
	{
		if ( !pQUEST_END->m_bCOMPLETE )
		{
			//	Æ÷±â(½ÇÆÐ)ÇÑ Äù½ºÆ®¸¦ ´Ù½Ã ½Ãµµ ºÒ°¡´ÉÇÑ °æ¿ì.
			if ( !pQUEST->IsAGAIN() )
			{
				CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMQUEST_START_FB_DONAGAIN") );
				return S_FALSE;
			}
		}
		else
		{
			//	¿©·¯¹ø ½Ãµµ °¡´ÉÇÑÁö °Ë»ç.
			if ( !pQUEST->IsREPEAT() )
			{
				CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMQUEST_START_FB_DONREPEAT") );
				return S_FALSE;
			}
		}
	}

	if ( m_lnMoney < pQUEST->m_dwBeginMoney )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMQUEST_START_FB_NEEDMONEY") );
		return S_FALSE;
	}

	//pQUEST->m_dwBeginPartyMemNum;

	//	Note : quest ½ÃÀÛ ¼­¹ö¿¡ ¿äÃ».
	//
	GLMSG::SNETPC_REQ_QUEST_START NetMsg;
	NetMsg.dwNpcID = dwNpcID;
	NetMsg.dwTalkID = dwTalkID;
	NetMsg.dwQuestID = dwQUESTID;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqQuestStepNpcTalk ( DWORD dwNpcID, DWORD dwTalkID, DWORD dwQUESTID, DWORD dwQUESTSTEP )
{
	//	Note : Äù½ºÆ® ÁøÇàÀ» À§ÇÑ °Ë»ç.
	//
	GLQUEST* pQUEST = GLQuestMan::GetInstance().Find ( dwQUESTID );
	if ( !pQUEST )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMQUEST_START_FB_FAIL") );
		return S_FALSE;
	}

	//	ÁøÇàÁßÀÎ Äù½ºÆ® ÀÎÁö Á¡°Ë.
	GLQUESTPROG* pQUEST_PROG = m_cQuestPlay.FindProc ( dwQUESTID );
	if ( !pQUEST_PROG )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMQUEST_START_FB_ALREADY") );
		return S_FALSE;
	}

	//	Note : quest ÁøÇà ¼­¹ö¿¡ ¿äÃ».
	//
	GLMSG::SNET_QUEST_STEP_NPC_TALK NetMsg;
	NetMsg.dwNpcID = dwNpcID;
	NetMsg.dwTalkID = dwTalkID;
	NetMsg.dwQUESTID = dwQUESTID;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqQuestGiveUp ( DWORD dwQUESTID )
{
	//	Note : Äù½ºÆ® ÁøÇàÀ» À§ÇÑ °Ë»ç.
	//
	GLQUEST* pQUEST = GLQuestMan::GetInstance().Find ( dwQUESTID );
	if ( !pQUEST )			return S_FALSE;

	//	ÁøÇàÁßÀÎ Äù½ºÆ® ÀÎÁö Á¡°Ë.
	GLQUESTPROG* pQUEST_PROG = m_cQuestPlay.FindProc ( dwQUESTID );
	if ( !pQUEST_PROG )		return S_FALSE;

	if ( !pQUEST->IsGIVEUP() )		return S_FALSE;

	//	Note : quest ÁøÇà ¼­¹ö¿¡ ¿äÃ».
	//
	GLMSG::SNET_QUEST_PROG_GIVEUP NetMsg;
	NetMsg.dwQUESTID = dwQUESTID;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqQuestREADINGReset ( DWORD dwQUESTID )
{
	//	Note : Äù½ºÆ® ÁøÇàÀ» À§ÇÑ °Ë»ç.
	//
	GLQUEST* pQUEST = GLQuestMan::GetInstance().Find ( dwQUESTID );
	if ( !pQUEST )			return S_FALSE;

	//	ÁøÇàÁßÀÎ Äù½ºÆ® ÀÎÁö Á¡°Ë.
	GLQUESTPROG* pQUEST_PROG = m_cQuestPlay.FindProc ( dwQUESTID );
	if ( !pQUEST_PROG )		return S_FALSE;

	if ( !pQUEST_PROG->IsReqREADING() )		return S_FALSE;
	pQUEST_PROG->ResetReqREADING ();

	//	Note : quest ÀÐ¾úÀ½À» ¼­¹ö¿¡ ¾Ë¸².
	//
	GLMSG::SNETPC_QUEST_PROG_READ NetMsg;
	NetMsg.dwQID = dwQUESTID;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqQuestComplete ( DWORD dwQUESTID )
{
	//	Note : Äù½ºÆ® ÁøÇàÀ» À§ÇÑ °Ë»ç.
	//
	GLQUEST* pQUEST = GLQuestMan::GetInstance().Find ( dwQUESTID );
	if ( !pQUEST )			return S_FALSE;

	//	ÁøÇàÁßÀÎ Äù½ºÆ® ÀÎÁö Á¡°Ë.
	GLQUESTPROG* pQUEST_PROG = m_cQuestPlay.FindProc ( dwQUESTID );
	if ( !pQUEST_PROG )		return S_FALSE;

	if ( !pQUEST_PROG->CheckCOMPLETE() )		return S_FALSE;

	//	Note : quest ¿Ï·á ¿äÃ».
	//
	GLMSG::SNETPC_REQ_QUEST_COMPLETE NetMsg;
	NetMsg.dwQID = dwQUESTID;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqBusStation ( DWORD dwNpcID, DWORD dwSTATION )
{
	PLANDMANCLIENT pLandMan = GLGaeaClient::GetInstance().GetActiveMap ();
	PGLCROWCLIENT pCrow = pLandMan->GetCrow ( dwNpcID );

	if ( !pCrow )										return S_OK;
	if ( pCrow->GETCROW() != CROW_NPC )					return S_OK;

	//	Note : Á¤·ùÀå id°¡ Á¤È®ÇÑÁö °Ë»ç.
	//
	SSTATION* pSTATION = GLBusStation::GetInstance().GetStation ( dwSTATION );
	if ( !pSTATION )									return S_OK;

	SMAPNODE *pNODE = GLGaeaClient::GetInstance().FindMapNode(SNATIVEID(pSTATION->dwMAPID));
	if ( !pNODE )										return S_OK;
	
	//	Note : pk µî±ÞÀÌ »ìÀÎÀÚ µî±Þ ÀÌ»óÀÏ °æ¿ì ¹ö½º »ç¿ëÀ» ¸·´Â´Ù.
	//
	//DWORD dwPK_LEVEL = GET_PK_LEVEL();
	//if ( dwPK_LEVEL != UINT_MAX && dwPK_LEVEL>GLCONST_CHAR::dwPK_RECALL_ENABLE_LEVEL )
	//{
	//	CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMBUS_TAKE_PK_LEVEL") );
	//	return E_FAIL;
	//}

	EMREQFAIL emReqFail(EMREQUIRE_COMPLETE);

	GLLevelFile cLevelFile;
	BOOL bOk = cLevelFile.LoadFile ( pNODE->strFile.c_str(), TRUE, NULL );
	if ( !bOk )											return S_OK;

	SLEVEL_REQUIRE* pRequire = cLevelFile.GetLevelRequire ();
	emReqFail = pRequire->ISCOMPLETE ( this );
	if ( emReqFail != EMREQUIRE_COMPLETE )
	{
		CInnerInterface &cINTERFACE = CInnerInterface::GetInstance();
		switch ( emReqFail )
		{
		case EMREQUIRE_LEVEL:
			{
				if( pRequire->m_signLevel == EMSIGN_FROMTO )
				{
					cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
						ID2GAMEINTEXT("EMREQUIRE_LEVEL2"),
						pRequire->m_wLevel,
						pRequire->m_wLevel2 );
				}else{
					std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signLevel].c_str());

					if( RANPARAM::emSERVICE_TYPE == EMSERVICE_THAILAND )
					{
						cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
							ID2GAMEINTEXT("EMREQUIRE_LEVEL"),
							strSIGN.c_str(),
							pRequire->m_wLevel );
					}
					else
					{
						cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
							ID2GAMEINTEXT("EMREQUIRE_LEVEL"),
							pRequire->m_wLevel,
							strSIGN.c_str() );
					}
				}
			}
			break;

		case EMREQUIRE_ITEM:
			{
				SITEM *pItem = GLItemMan::GetInstance().GetItem ( pRequire->m_sItemID );
				if ( pItem )
				{
					cINTERFACE.PrintMsgTextDlg
					(
						NS_UITEXTCOLOR::DISABLE,
						ID2GAMEINTEXT("EMREQUIRE_ITEM"),
						pItem->GetName()
					);
				}
			}
			break;

		case EMREQUIRE_SKILL:
			{
				PGLSKILL pSkill = GLSkillMan::GetInstance().GetData ( pRequire->m_sSkillID );
				if ( pSkill )
				{
					cINTERFACE.PrintMsgTextDlg
					(
						NS_UITEXTCOLOR::DISABLE,
						ID2GAMEINTEXT("EMREQUIRE_SKILL"),
						pSkill->GetName()
					);
				}
			}
			break;

		case EMREQUIRE_LIVING:
			{
				std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signLiving].c_str());
				cINTERFACE.PrintMsgTextDlg
				(
					NS_UITEXTCOLOR::DISABLE,
					ID2GAMEINTEXT("EMREQUIRE_LIVING"),
					pRequire->m_nLiving,
					strSIGN.c_str()
				);
			}
			break;

		case EMREQUIRE_BRIGHT:
			{
				std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signBright].c_str());
				cINTERFACE.PrintMsgTextDlg
				(
					NS_UITEXTCOLOR::DISABLE,
					ID2GAMEINTEXT("EMREQUIRE_BRIGHT"),
					pRequire->m_nBright,
					strSIGN.c_str()
				);
			}
			break;

		case EMREQUIRE_QUEST_COM:
			{
				CString strQUEST = "quest";
				GLQUEST *pQUEST = GLQuestMan::GetInstance().Find ( pRequire->m_sComQuestID.dwID );
				if ( pQUEST )		strQUEST = pQUEST->GetTITLE();

				cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQUIRE_QUEST_COM"), strQUEST.GetString() );
			}
			break;

		case EMREQUIRE_QUEST_ACT:
			{
				CString strQUEST = "quest";
				GLQUEST *pQUEST = GLQuestMan::GetInstance().Find ( pRequire->m_sComQuestID.dwID );
				if ( pQUEST )		strQUEST = pQUEST->GetTITLE();

				cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQUIRE_QUEST_ACT"), strQUEST.GetString() );
			}
			break;

		default:
			cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("MAP_REQUIRE_FAIL") );
			break;
		};

		//	Note : Ãâ±¸ »ç¿ë ±ÇÇÑÀÌ ¾ÈµÉ °æ¿ì. GM level ÀÌ»óÀÏ °æ¿ì Á¶°Ç ¹«½Ã.
		//
		if ( m_dwUserLvl < USER_GM3 )	return S_OK;
	}

	//	Note : ¹ö½º Æ¼ÄÏÀÌ ÀÖ´ÂÁö °Ë»ç.
	//
	SINVENITEM* pITEM_TICKET = m_cInventory.FindItem ( ITEM_TICKET );
	if ( !pITEM_TICKET )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMBUS_TAKE_TICKET") );
		return S_OK;
	}

	//	Note : ¹ö½º ½ÂÂ÷.
	//
	GLMSG::SNETPC_REQ_BUS NetMsg;
	NetMsg.wPosX = pITEM_TICKET->wPosX;
	NetMsg.wPosY = pITEM_TICKET->wPosY;
	NetMsg.dwNPC_ID = dwNpcID;
	NetMsg.dwSTATION_ID = dwSTATION;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

	//	Note : ÅÃ½Ã ½ÂÂ÷ ¿äÃ».
HRESULT GLCharacter::ReqTaxiStation ( WORD wPosX, WORD wPosY, int nSelectMap, int nSelectStop )
{
	//	Note : Á¤·ùÀå id°¡ Á¤È®ÇÑÁö °Ë»ç.
	//
	STAXI_MAP* pTaxiMap = GLTaxiStation::GetInstance().GetTaxiMap ( nSelectMap );
	if ( !pTaxiMap )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMTAXI_TAKE_MAPFAIL") );
		return S_OK;
	}

	STAXI_STATION* pStation = pTaxiMap->GetStation( nSelectStop );
	if ( !pStation )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMTAXI_TAKE_STATIONFAIL") );
		return S_OK;
	}

	SMAPNODE *pNODE = GLGaeaClient::GetInstance().FindMapNode(SNATIVEID(pStation->dwMAPID));
	if ( !pNODE )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMTAXI_TAKE_MAPFAIL") );
		return S_OK;
	}

	EMREQFAIL emReqFail(EMREQUIRE_COMPLETE);

	GLLevelFile cLevelFile;
	BOOL bOk = cLevelFile.LoadFile ( pNODE->strFile.c_str(), TRUE, NULL );
	if ( !bOk )											return S_OK;

	SLEVEL_REQUIRE* pRequire = cLevelFile.GetLevelRequire ();
	emReqFail = pRequire->ISCOMPLETE ( this );
	if ( emReqFail != EMREQUIRE_COMPLETE )
	{
		CInnerInterface &cINTERFACE = CInnerInterface::GetInstance();
		switch ( emReqFail )
		{
		case EMREQUIRE_LEVEL:
			{
				if( pRequire->m_signLevel == EMSIGN_FROMTO )
				{
					cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
						ID2GAMEINTEXT("EMREQUIRE_LEVEL2"),
						pRequire->m_wLevel,
						pRequire->m_wLevel2 );
				}else{
					std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signLevel].c_str());

					if( RANPARAM::emSERVICE_TYPE == EMSERVICE_THAILAND )
					{
						cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
							ID2GAMEINTEXT("EMREQUIRE_LEVEL"),
							strSIGN.c_str(),
							pRequire->m_wLevel );
					}
					else
					{
						cINTERFACE.PrintMsgTextDlg( NS_UITEXTCOLOR::DISABLE, 
							ID2GAMEINTEXT("EMREQUIRE_LEVEL"),
							pRequire->m_wLevel,
							strSIGN.c_str() );
					}
				}
			}
			break;

		case EMREQUIRE_ITEM:
			{
				SITEM *pItem = GLItemMan::GetInstance().GetItem ( pRequire->m_sItemID );
				if ( pItem )
				{
					cINTERFACE.PrintMsgTextDlg
					(
						NS_UITEXTCOLOR::DISABLE,
						ID2GAMEINTEXT("EMREQUIRE_ITEM"),
						pItem->GetName()
					);
				}
			}
			break;

		case EMREQUIRE_SKILL:
			{
				PGLSKILL pSkill = GLSkillMan::GetInstance().GetData ( pRequire->m_sSkillID );
				if ( pSkill )
				{
					cINTERFACE.PrintMsgTextDlg
					(
						NS_UITEXTCOLOR::DISABLE,
						ID2GAMEINTEXT("EMREQUIRE_SKILL"),
						pSkill->GetName()
					);
				}
			}
			break;

		case EMREQUIRE_LIVING:
			{
				std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signLiving].c_str());
				cINTERFACE.PrintMsgTextDlg
				(
					NS_UITEXTCOLOR::DISABLE,
					ID2GAMEINTEXT("EMREQUIRE_LIVING"),
					pRequire->m_nLiving,
					strSIGN.c_str()
				);
			}
			break;

		case EMREQUIRE_BRIGHT:
			{
				std::string strSIGN = ID2GAMEINTEXT(COMMENT::CDT_SIGN_ID[pRequire->m_signBright].c_str());
				cINTERFACE.PrintMsgTextDlg
				(
					NS_UITEXTCOLOR::DISABLE,
					ID2GAMEINTEXT("EMREQUIRE_BRIGHT"),
					pRequire->m_nBright,
					strSIGN.c_str()
				);
			}
			break;

		case EMREQUIRE_QUEST_COM:
			{
				CString strQUEST = "quest";
				GLQUEST *pQUEST = GLQuestMan::GetInstance().Find ( pRequire->m_sComQuestID.dwID );
				if ( pQUEST )		strQUEST = pQUEST->GetTITLE();

				cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQUIRE_QUEST_COM"), strQUEST.GetString() );
			}
			break;

		case EMREQUIRE_QUEST_ACT:
			{
				CString strQUEST = "quest";
				GLQUEST *pQUEST = GLQuestMan::GetInstance().Find ( pRequire->m_sComQuestID.dwID );
				if ( pQUEST )		strQUEST = pQUEST->GetTITLE();

				cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQUIRE_QUEST_ACT"), strQUEST.GetString() );
			}
			break;

		default:
			cINTERFACE.PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("MAP_REQUIRE_FAIL") );
			break;
		};

		//	Note : Ãâ±¸ »ç¿ë ±ÇÇÑÀÌ ¾ÈµÉ °æ¿ì. GM level ÀÌ»óÀÏ °æ¿ì Á¶°Ç ¹«½Ã.
		//
		if ( m_dwUserLvl < USER_GM3 )	return S_OK;
	}

	//	Note : ÅÃ½Ã Ä«µå°¡ ÀÖ´ÂÁö °Ë»ç.
	//
	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem || pItem->sBasicOp.emItemType != ITEM_TAXI_CARD )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMTAXI_TAKE_TICKET") );
		return E_FAIL;
	}

	LONGLONG lnCharge = GetCalcTaxiCharge( nSelectMap, nSelectStop );

	if ( m_lnMoney < lnCharge )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMTAXI_TAKE_MONEY") );
		return S_FALSE;
	}

	//	Note : ¹ö½º ½ÂÂ÷.
	//
	GLMSG::SNETPC_REQ_TAXI NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NetMsg.dwSelectMap = nSelectMap;
	NetMsg.dwSelectStop = nSelectStop;
	NetMsg.dwGaeaID = m_dwGaeaID;
	NETSEND ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqLoudSpeaker ( const char* szChat, SITEMLINK* pItemLink )
{
	WORD wPosX(0), wPosY(0);

	if ( m_dwUserLvl < USER_GM3 )
	{
		SINVENITEM* pINVENITEM = m_cInventory.FindItem ( ITEM_LOUDSPEAKER );
		if ( !pINVENITEM )
		{
			//	Note : È®¼º±â ¾ÆÀÌÅÛÀÌ Á¸Á¦ÇÏÁö ¾Ê½À´Ï´Ù.
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMCHAT_LOUDSPEAKER_NOITEM") );
			return S_OK;
		}

		if ( m_sPMarket.IsOpen() ) 
		{
			WORD wTurn = m_cInventory.CountTurnItem( pINVENITEM->sItemCustom.sNativeID );
			WORD wMarketTurn = m_sPMarket.GetItemTurnNum( pINVENITEM->sItemCustom.sNativeID ) ;

			if ( wTurn <= wMarketTurn ) 
			{
				//	Note : È®¼º±â ¾ÆÀÌÅÛÀÌ Á¸Á¦ÇÏÁö ¾Ê½À´Ï´Ù.
				CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMCHAT_LOUDSPEAKER_NOITEM") );
				return S_OK;
			}
		}

		wPosX = pINVENITEM->wPosX;
		wPosY = pINVENITEM->wPosY;
	}

	//	Note : È®¼º±â »ç¿ë ¿äÃ».
	//
	GLMSG::SNETPC_CHAT_LOUDSPEAKER NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	if ( pItemLink )
		NetMsg.sITEMLINK = *pItemLink;
	StringCchCopy ( NetMsg.szMSG, CHAT_MSG_SIZE+1, szChat );
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqPMarketTitle ( const char* szTitle )
{
	if ( m_sPMarket.IsOpen() )	return S_FALSE;

	//	Note : ÃÊ±âÈ­¸¦ ÇàÇÑ´Ù.
	m_sPMarket.DoMarketClose();

	//	Note : Å¸ÀÌÆ² ÁöÁ¤ ¿äÃ».
	//
	GLMSG::SNETPC_PMARKET_TITLE NetMsg;
	StringCchCopy ( NetMsg.szPMarketTitle, CHAT_MSG_SIZE+1, szTitle );
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqPMarketRegItem ( WORD wPosX, WORD wPosY, LONGLONG dwMoney, DWORD dwNum )
{
	if ( m_sPMarket.IsOpen() )	return S_FALSE;

	SINVENITEM *pINVENITEM = m_cInventory.GetItem ( wPosX, wPosY );
	if ( !pINVENITEM )			return S_FALSE;

	//	Note : µî·ÏÇÒ ¼ö ÀÖ´Â ÇÑµµ¸¦ ³Ñ¾î¼­°í ÀÖ½À´Ï´Ù.
	if ( m_sPMarket.GetItemNum() >= GLPrivateMarket::EMMAX_SALE_NUM )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_REGITEM_FB_MAXNUM") );
		return S_FALSE;
	}

	SNATIVEID nidITEM = pINVENITEM->sItemCustom.sNativeID;

	SITEM *pITEM = GLItemMan::GetInstance().GetItem ( nidITEM );
	if ( !pITEM )				return S_FALSE;

	//	°Å·¡¿É¼Ç
	if ( !pINVENITEM->sItemCustom.IsWrap() )
	{
	if ( !pITEM->sBasicOp.IsEXCHANGE() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( 
			NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_REGITEM_FB_NOSALE") );
		return S_FALSE;
	}
	if ( pINVENITEM->sItemCustom.IsNonDrop()  )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( 
			NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_REGITEM_FB_NOSALE") );
		return S_FALSE;
	}
	}

	// ÆÖÄ«µåÀÌ¸é¼­ ÆÖÀÌ È°¼ºÈ­ µÇ¾î ÀÖÀ¸¸é »óÁ¡¿¡ µî·ÏÇÒ ¼ö ¾ø´Ù.
	if ( pITEM->sBasicOp.emItemType == ITEM_PET_CARD )
	{
		// µé°íÀÖ´Â ¾ÆÀÌÅÛÀÌ ÆÖÄ«µåÀÌ¸ç ÆÖÀÌ È°¼ºÈ­ µÇ¾î ÀÖÀ¸¸é
		GLPetClient* pMyPet = GLGaeaClient::GetInstance().GetPetClient ();
		if ( pMyPet->IsVALID () && pINVENITEM->sItemCustom.dwPetID == pMyPet->m_dwPetID )
		{
			return E_FAIL;
		}
	}

	if ( pITEM->sBasicOp.emItemType == ITEM_VEHICLE && pINVENITEM->sItemCustom.dwVehicleID != 0 )
	{
		return E_FAIL;
	}

	//	Note : ÀÌ¹Ì µî·ÏµÈ ¾ÆÀÌÅÛÀÎÁö °Ë»ç.
	bool bREGPOS = m_sPMarket.IsRegInvenPos ( SNATIVEID(wPosX,wPosY) );
	if ( bREGPOS )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_REGITEM_FB_REGITEM") );
		return S_FALSE;
	}

	//	Note : °ãÄ§ °¡´ÉÀÏ °æ¿ì µ¿ÀÏÇÑ Á¾·ùÀÇ ¾ÆÀÌÅÛÀÌ ÀÌ¹Ì µî·ÏµÇ¾î ÀÖ´ÂÁö °Ë»ç.
	if ( pITEM->ISPILE() )
	{
		bool bREG = m_sPMarket.IsRegItem ( nidITEM );
		if ( bREG )
		{
			CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_REGITEM_FB_REGITEM") );
			return S_FALSE;
		}

		//	Note : ¼ÒÁöÇÏ°í ÀÖ´Â °³¼ö ¸¹Å­ ÆÇ¸Å °¡´É.
		DWORD dwTURN = m_cInventory.CountTurnItem ( nidITEM );
		if ( dwNum >= dwTURN )
		{
			dwNum = dwTURN;
		}
	}

	if ( !pITEM->ISPILE() )
	{
		dwNum = 1;
	}
	
	SNATIVEID sSALEPOS;
	bool bPOS = m_sPMarket.FindInsertPos ( nidITEM, sSALEPOS );
	if ( !bPOS )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_REGITEM_FB_MAXNUM") );
		return S_FALSE;
	}

	GLMSG::SNETPC_PMARKET_REGITEM NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NetMsg.dwMoney = dwMoney;
	NetMsg.dwNum = dwNum;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqPMarketDisItem ( WORD wPosX, WORD wPosY )
{
	if ( m_sPMarket.IsOpen() )	return S_FALSE;

	SNATIVEID sSALEPOS(wPosX,wPosY);
	const SSALEITEM* pSALEITEM = m_sPMarket.GetItem ( sSALEPOS );
	if ( !pSALEITEM )		return S_FALSE;

	GLMSG::SNETPC_PMARKET_DISITEM NetMsg;
	NetMsg.sSALEPOS = sSALEPOS;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqPMarketOpen ()
{
	if ( m_sPMarket.IsOpen() )		return S_FALSE;

	//	Note : ÆÇ¸Å¿ëÀ¸·Î µî·ÏÇÑ ¾ÆÀÌÅÛÀÌ 
	if ( m_sPMarket.GetItemNum() == 0 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_OPEN_FB_EMPTY") );
		return S_FALSE;
	}

	if ( m_wPMPosX==USHRT_MAX )		return S_FALSE;
	if ( m_wPMPosY==USHRT_MAX )		return S_FALSE;

	//	Note : °³ÀÎ»óÁ¡ °³¼³ ¿äÃ».
	GLMSG::SNETPC_PMARKET_OPEN NetMsg;
	NetMsg.wPosX = m_wPMPosX;
	NetMsg.wPosY = m_wPMPosY;
	NETSENDTOFIELD ( &NetMsg );

	m_wPMPosX = (USHRT_MAX);
	m_wPMPosY = (USHRT_MAX);

	return S_OK;
}

HRESULT GLCharacter::ReqPMarketClose ()
{
	if ( !m_sPMarket.IsOpen() )		return S_FALSE;

	m_sPMarket.DoMarketClose();

	GLMSG::SNETPC_PMARKET_CLOSE NetMsg;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqPMarketInfo ( DWORD dwGaeaID )
{
	PGLCHARCLIENT pCLIENT = GLGaeaClient::GetInstance().GetChar ( dwGaeaID );
	if ( !pCLIENT )					return S_FALSE;

	bool bOPEN = pCLIENT->m_sPMarket.IsOpen();
	if ( !bOPEN )
	{
		return S_FALSE;
	}

	//	Note : Á¤º¸ Àü¼Û ¿äÃ»Àü Á¾Àü Á¤º¸´Â ¸®¼Â.
	pCLIENT->m_sPMarket.DoMarketInfoRelease();

	//	Note : Á¤º¸ Àü¼Û ¿äÃ».
	GLMSG::SNETPC_PMARKET_ITEM_INFO NetMsg;
	NetMsg.dwGaeaID = dwGaeaID;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqPMarketInfoRelease ( DWORD dwGaeaID )
{
	PGLCHARCLIENT pCLIENT = GLGaeaClient::GetInstance().GetChar ( dwGaeaID );
	if ( !pCLIENT )					return S_FALSE;

	pCLIENT->m_sPMarket.DoMarketInfoRelease();

	// ´Ù¸¥»ç¶÷ °³ÀÎ»óÁ¡À» ´ÝÀ¸¸é ÀÓ½Ã ¼ö½ÅµÈ ÆÖÄ«µå Á¤º¸µµ Á¦°ÅÇØÁØ´Ù.
	m_mapPETCardInfoTemp.clear();
	m_mapVEHICLEItemInfoTemp.clear();

	return S_OK;
}

HRESULT GLCharacter::ReqPMarketBuy ( DWORD dwGaeaID, WORD wPosX, WORD wPosY, DWORD dwNum )
{
	PGLCHARCLIENT pCLIENT = GLGaeaClient::GetInstance().GetChar ( dwGaeaID );
	if ( !pCLIENT )					return S_FALSE;

	SNATIVEID sSALEPOS(wPosX,wPosY);

	const SSALEITEM* pSALEITEM = pCLIENT->m_sPMarket.GetItem ( sSALEPOS );
	if ( !pSALEITEM )				return S_FALSE;

	SITEM *pITEM = GLItemMan::GetInstance().GetItem ( pSALEITEM->sITEMCUSTOM.sNativeID );
	if ( !pITEM )					return S_FALSE;

	if ( pSALEITEM->bSOLD )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_BUY_FB_SOLD") );
		return S_FALSE;
	}

	if ( !pITEM->ISPILE() )
	{
		dwNum = 1;
	}

	if ( pSALEITEM->dwNUMBER < dwNum )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_BUY_FB_NUM") );
		return S_FALSE;
	}

	if ( m_lnMoney < (dwNum*pSALEITEM->llPRICE) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_BUY_FB_LOWMONEY") );
		return S_FALSE;
	}

	WORD wINVENX = pITEM->sBasicOp.wInvenSizeX;
	WORD wINVENY = pITEM->sBasicOp.wInvenSizeY;

	BOOL bITEM_SPACE(FALSE);
	if ( pITEM->ISPILE() )
	{
		WORD wPILENUM = pITEM->sDrugOp.wPileNum;
		SNATIVEID sNID = pITEM->sBasicOp.sNativeID;
		WORD wREQINSRTNUM = (WORD) dwNum;

		bITEM_SPACE = m_cInventory.ValidPileInsrt ( wREQINSRTNUM, sNID, wPILENUM, wINVENX, wINVENY );
	}
	else
	{
		WORD wPosX(0), wPosY(0);
		bITEM_SPACE = m_cInventory.FindInsrtable ( wINVENX, wINVENY, wPosX, wPosY );
	}

	if ( !bITEM_SPACE )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPMARKET_BUY_FB_NOINVEN") );
		return E_FAIL;
	}

	GLMSG::SNETPC_PMARKET_BUY NetMsg;
	NetMsg.dwGaeaID = dwGaeaID;
	NetMsg.dwNum = dwNum;
	NetMsg.sSALEPOS = sSALEPOS;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqClubNew ( DWORD dwNpcID, const char* szClubName )
{
	if  ( szClubName==NULL )	return S_FALSE;

	if ( m_dwGuild!=CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_ALREADY") );
		return S_FALSE;
	}

	if ( !GLPartyClient::GetInstance().GetMaster() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( GLPartyClient::GetInstance().GetMemberNum() < GLCONST_CHAR::dwCLUB_PARTYNUM )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_NOTMEMBER"), GLCONST_CHAR::dwCLUB_PARTYNUM );
		return S_FALSE;
	}

	if ( !szClubName || (strlen(szClubName) == 0) )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_FAIL") );
		return S_FALSE;
	}

	if ( m_wLevel < GLCONST_CHAR::sCLUBRANK[0].m_dwMasterLvl )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_MASTER_LVL"), GLCONST_CHAR::sCLUBRANK[0].m_dwMasterLvl );
		return S_FALSE;
	}

	if ( m_nLiving < int ( GLCONST_CHAR::sCLUBRANK[0].m_dwLivingPoint ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_AVER_LIVING"), GLCONST_CHAR::sCLUBRANK[0].m_dwLivingPoint );
		return S_FALSE;
	}

	if ( m_lnMoney < GLCONST_CHAR::sCLUBRANK[0].m_dwPay )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_NOTMONEY"), GLCONST_CHAR::sCLUBRANK[0].m_dwPay );
		return S_FALSE;
	}


	CString strTEMP( szClubName ); 

#ifdef TH_PARAM
	// ÅÂ±¹¾î ¹®ÀÚ Á¶ÇÕ Ã¼Å©

	if ( !m_pCheckString ) return S_FALSE;

	if ( !m_pCheckString(strTEMP) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_THAICHAR_ERROR"));
		return S_FALSE;
	}
#endif

#ifdef VN_PARAM
	// º£Æ®³² ¹®ÀÚ Á¶ÇÕ Ã¼Å© 
	if( STRUTIL::CheckVietnamString( strTEMP ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_VNCHAR_ERROR"));
		return S_FALSE;
	}

#endif 

	BOOL bFILTER0 = STRUTIL::CheckString( strTEMP );;
	BOOL bFILTER1 = CRanFilter::GetInstance().NameFilter ( szClubName );
	if( bFILTER0 || bFILTER1 || ( strTEMP != szClubName ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NEW_FB_BADNAME"), szClubName );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_NEW NetMsg;
	NetMsg.dwNpcID = dwNpcID;
	StringCchCopy ( NetMsg.szClubName, CHAR_SZNAME, szClubName );
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqClubRank ( DWORD dwNpcID )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		return S_FALSE;
	}

	if ( m_sCLUB.m_dwRank >= (GLCONST_CHAR::dwMAX_CLUBRANK-1) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_RANK_FB_MAX") );
		return S_FALSE;
	}

	DWORD dwRANK = m_sCLUB.m_dwRank + 1;

	if ( m_wLevel < GLCONST_CHAR::sCLUBRANK[dwRANK].m_dwMasterLvl )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_RANK_FB_MASTER_LVL"), GLCONST_CHAR::sCLUBRANK[dwRANK].m_dwMasterLvl );
		return S_FALSE;
	}

	if ( m_nLiving < int ( GLCONST_CHAR::sCLUBRANK[dwRANK].m_dwLivingPoint ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_RANK_FB_AVER_LIVING"), GLCONST_CHAR::sCLUBRANK[dwRANK].m_dwLivingPoint );
		return S_FALSE;
	}

	if ( m_lnMoney < GLCONST_CHAR::sCLUBRANK[dwRANK].m_dwPay )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_RANK_FB_NOTMONEY"), GLCONST_CHAR::sCLUBRANK[dwRANK].m_dwPay );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_RANK NetMsg;
	NetMsg.dwNpcID = dwNpcID;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqClubDissolution ()
{
	if ( m_dwGuild==CLUB_NULL )
	{
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		return S_FALSE;
	}

	if ( m_sCLUB.IsAlliance() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_DIS_FB_ALLIANCE") );
		return S_FALSE;
	}

	if ( m_sCLUB.GetAllBattleNum() > 0 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_DIS_FB_CLUBBATTLE") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_DISSOLUTION NetMsg;
	NETSEND(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqClubDissolutionCancel ()
{
	if ( m_dwGuild==CLUB_NULL )
	{
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_DISSOLUTION NetMsg;
	NetMsg.bCANCEL = true;
	NETSEND(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqClubJoin ( DWORD dwGaeaID )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMemberFlgJoin(m_dwCharID) )
	{
		return S_FALSE;
	}

	if ( m_sCLUB.GetAllBattleNum() > 0 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_MEMBER_REQ_FB_CLUBBATTLE") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_MEMBER_REQ NetMsg;
	NetMsg.dwGaeaID = dwGaeaID;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqClubJoinAns ( DWORD dwMaster, bool bOK )
{
	GLMSG::SNET_CLUB_MEMBER_REQ_ANS NetMsg;
	NetMsg.dwMaster = dwMaster;
	NetMsg.bOK = bOK;
	NETSENDTOFIELD(&NetMsg);
	
	return S_OK;
}

HRESULT GLCharacter::ReqClubMemberDel ( DWORD dwMember )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMemberFlgKick(m_dwCharID) )
	{
		return S_FALSE;
	}

	if ( m_sCLUB.m_dwMasterID==dwMember )
	{
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_MEMBER_DEL NetMsg;
	NetMsg.dwMember = dwMember;
	NETSEND(&NetMsg);
	
	return S_OK;
}

	//	Note : Å¬·´ ¸¶½ºÅÍ ±ÇÇÑ À§ÀÓ
HRESULT GLCharacter::ReqClubAuthority ( DWORD dwMember )
{
	if ( m_dwGuild==CLUB_NULL )	return S_FALSE;
	if ( !m_sCLUB.IsMaster( m_dwCharID ) )	return S_FALSE;
	if ( m_sCLUB.m_dwMasterID==dwMember )	return S_FALSE;

	//	Å¬·´¿ø È®ÀÎ
	GLCLUBMEMBER* pMember = m_sCLUB.GetMember( dwMember );
	if ( !pMember )	return S_FALSE;

	//	Á¢¼Ó ¿©ºÎ È®ÀÎ
	if ( !pMember->bONLINE ) 
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_AUTHORITY_REQ_FB_NOONLINE") );
		return S_FALSE;
	}

	//	µ¿¸Í ¿©ºÎ
	if ( m_sCLUB.IsAlliance() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_AUTHORITY_REQ_FB_ALLIANCE") );
		return S_FALSE;
	}

	//	Å¬·´¹èÆ² ¿©ºÎ
	if ( m_sCLUB.GetAllBattleNum() > 0 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_AUTHORITY_REQ_FB_CLUBBATTLE") );
		return S_FALSE;
	}

    //	´ë·Ã ¿©ºÎ
	if ( m_sCONFTING.emTYPE != EMCONFT_NONE )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_AUTHORITY_REQ_FB_CONFING") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_AUTHORITY_REQ NetMsg;
	NetMsg.dwMember = dwMember;
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : Å¬·´ ¸¶½ºÅÍ ±ÇÇÑ À§ÀÓ ´äº¯
HRESULT GLCharacter::ReqClubAuthorityAns ( bool bOK )
{
	GLMSG::SNET_CLUB_AUTHORITY_REQ_ANS NetMsgAns;
	NetMsgAns.bOK = bOK;
	NETSEND(&NetMsgAns);

	return S_OK;
}

HRESULT GLCharacter::ReqClubMarkInfo ( DWORD dwClubID, DWORD dwMarkVer )
{
	if ( dwMarkVer==0 )					return S_FALSE;

	//	Note : Å¬¶óÀÌ¾ðÆ® ¹öÀü°ú ºñ±³.
	//
	DWORD dwServerID = GLGaeaClient::GetInstance().GetCharacter()->m_dwServerID;
	bool bVALID = DxClubMan::GetInstance().IsValidData ( dwServerID, dwClubID, dwMarkVer );
	if ( bVALID )						return S_FALSE;

	//	Note : Å¬·´ ¸¶Å© °»½Å ¿äÃ».
	GLMSG::SNET_CLUB_MARK_INFO NetMsgInfo;
	NetMsgInfo.dwClubID = dwClubID;
	NETSEND(&NetMsgInfo);

	return S_OK;
}

HRESULT GLCharacter::ReqClubMarkChange ( const char* szFileName )
{
	if ( !szFileName )	return E_FAIL;

	// Note : ÆÄÀÏ¿¡¼­ ÀÐ¾î¿À±â.
	LPDWORD pMarkColor(NULL);
	BOOL bOK = DxClubMan::GetInstance().LoadClubMark ( szFileName, pMarkColor );
	if ( !bOK )
	{
		CInnerInterface::GetInstance().PrintConsoleTextDlg ( ID2GAMEINTEXT("CLUB_LOADMARK_FAIL"), szFileName );
		return E_FAIL;
	}

	//	Note : Å¬·´ ¸¶Å© °»½Å ¿äÃ».
	GLMSG::SNET_CLUB_MARK_CHANGE NetMsgChange;
	memcpy ( NetMsgChange.aryMark, pMarkColor, sizeof(DWORD)*EMCLUB_MARK_SX*EMCLUB_MARK_SY);
	NETSEND(&NetMsgChange);

	return S_OK;
}

HRESULT GLCharacter::ReqClubNick ( const char* szNickName )
{
	if ( !szNickName )					return S_FALSE;
	//if ( strlen(szNickName)==0 )		return S_FALSE;


	CString strTEMP = szNickName;

#ifdef TH_PARAM
	// ÅÂ±¹¾î ¹®ÀÚ Á¶ÇÕ Ã¼Å©

	if ( !m_pCheckString ) return S_FALSE;

	if ( !m_pCheckString(strTEMP) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_MEMBER_NICK_FB_THAICHAR_ERROR"));
		return S_FALSE;
	}
#endif

#ifdef VN_PARAM
	// º£Æ®³² ¹®ÀÚ Á¶ÇÕ Ã¼Å© 
	if( STRUTIL::CheckVietnamString( strTEMP ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_MEMBER_NICK_FB_VNCHAR_ERROR"));
		return S_FALSE;
	}

#endif 
	
	BOOL bFILTER0 = STRUTIL::CheckString( strTEMP );
	BOOL bFILTER1 = CRanFilter::GetInstance().NameFilter ( szNickName );
	if ( bFILTER0 || bFILTER1 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_MEMBER_NICK_FB_BADNAME") );
		return S_FALSE;
	}

	//	Note : Å¬·´ ¸â¹ö º°¸í.
	GLMSG::SNET_CLUB_MEMBER_NICK NetMsg;
	StringCchCopy ( NetMsg.szNick, CHAR_SZNAME, szNickName );
	NETSEND(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqClubSecede ()
{
	if ( m_dwGuild==CLUB_NULL )
	{
		return S_FALSE;
	}

	//	Note : Å¬·´ÀåÀº Å»Åð¸¦ ÇÒ¼ö ¾ø´Ù.
	if ( m_sCLUB.IsMaster(m_dwCharID) )
	{
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_MEMBER_SECEDE NetMsg;
	NETSEND(&NetMsg);
	
	return S_OK;
}

HRESULT GLCharacter::ReqCDCertify (DWORD dwNpcID )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCDCERTIFY_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMemberFlgCDCertify(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCDCERTIFY_NOTMASTER") );
		return S_FALSE;
	}

	PLANDMANCLIENT pLand = GLGaeaClient::GetInstance().GetActiveMap();
	if ( !pLand )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCDCERTIFY_FAIL") );
		return S_FALSE;
	}

	PGLCROWCLIENT pCROW = pLand->GetCrow(dwNpcID);
	if ( !pCROW )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCDCERTIFY_FAIL") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_CD_CERTIFY NetMsg;
	NetMsg.dwNpcID = dwNpcID;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}
HRESULT GLCharacter::ReqDetectAP ( BOOL bDetect, DWORD dwCharID )
{

	GLMSG::SNET_AP_DT NetMsg;
	NetMsg.bDetect = bDetect;
	NetMsg.dwCharID = dwCharID;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}
HRESULT GLCharacter::ReqDetectWPE ( BOOL bDetect, DWORD dwCharID )
{
  if ( m_dwCharID!=dwCharID ) return S_FALSE;
  if ( !bDetect )  return S_FALSE;
  if  ( bDetect ) m_bDisconnect = TRUE;

  return S_OK;
}
HRESULT GLCharacter::ReqGuidCommission ( DWORD dwNPCID, float fRATE )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMGUIDCOMMISSION_FB_NOTCLUB") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMGUIDCOMMISSION_FB_NOTCLUB") );
		return S_FALSE;
	}

	PLANDMANCLIENT pLand = GLGaeaClient::GetInstance().GetActiveMap();
	if ( !pLand )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMGUIDCOMMISSION_FB_FAIL") );
		return S_FALSE;
	}

	PGLCROWCLIENT pCROW = pLand->GetCrow(dwNPCID);
	if ( !pCROW )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMGUIDCOMMISSION_FB_FAIL") );
		return S_FALSE;
	}

	if ( fRATE < 0 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMGUIDCOMMISSION_FB_RANGE") );
		return S_FALSE;
	}

	if ( fRATE > GLCONST_CHAR::fMAX_COMMISSION )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMGUIDCOMMISSION_FB_RANGE") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_GUID_COMMISSION NetMsg;
	NetMsg.fCommission = fRATE;
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : Å¬·´ °øÁö.
HRESULT GLCharacter::ReqClubNotice ( const char* szClubNotice )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NOTICE_FB_FAIL") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMemberFlgNotice(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_NOTICE_FB_NOTMATER") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_NOTICE_REQ NetMsg;
	StringCchCopy ( NetMsg.szNotice, EMCLUB_NOTICE_LEN+1, szClubNotice );
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : Å¬·´ ºÎ¸¶ ¼³Á¤.
HRESULT GLCharacter::ReqClubSubMaster ( DWORD dwCharID, DWORD dwClubFlag )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUBSUBMASTER_FB_FAIL") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUBSUBMASTER_FB_NOTMASTER") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_SUBMASTER NetMsg;
	NetMsg.dwCharID = dwCharID;
	NetMsg.dwFlags = dwClubFlag;
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : Å¬·´ ¿¬ÇÕ ¿äÃ».
HRESULT GLCharacter::ReqClubAlliance ( DWORD dwGaeaID )
{
	PGLCHARCLIENT pCHAR_REQ = GLGaeaClient::GetInstance().GetChar ( dwGaeaID );
	if ( !pCHAR_REQ )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_REQ_FB_FAIL") );
		return S_FALSE;
	}

	DWORD dwCharID = pCHAR_REQ->GetCharData().dwCharID;

	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_REQ_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_REQ_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !pCHAR_REQ->IsClubMaster() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_REQ_FB_TARNOTMASTER") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsAlliance() && !m_sCLUB.IsChief() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_REQ_FB_NOTCHIEF") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsRegDissolution() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_REQ_FB_DISSOLUTION") );
		return S_FALSE;
	}	

	if ( m_sCLUB.GetAllBattleNum() > 0  )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_REQ_FB_CLUBBATTLE") );
		return S_FALSE;
	}	
	
	GLMSG::SNET_CLUB_ALLIANCE_REQ NetMsg;
	NetMsg.dwCharID = dwCharID;
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : Å¬·´ µ¿¸Í ¿äÃ» ´äº¯.
HRESULT GLCharacter::ReqClubAllianceAns ( DWORD dwChiefCharID, bool bOK )
{
	GLMSG::SNET_CLUB_ALLIANCE_REQ_ANS NetMsgAns;
	NetMsgAns.dwChiefCharID = dwChiefCharID;
	NetMsgAns.bOK = bOK;
	NETSEND(&NetMsgAns);

	return S_OK;
}

//	Note : Å¬·´ µ¿¸Í Å»Åð ¿äÃ».
HRESULT GLCharacter::ReqClubAllianceSec ()
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_SEC_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_SEC_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsAlliance() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_SEC_FB_ALLIANCE") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsChief() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_SEC_FB_FAIL") );
		return S_FALSE;
	}

	if ( m_sCLUB.GetAllBattleNum() > 0 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_SEC_FB_BATTLE") );
		return S_FALSE;		
	}

	GLMSG::SNET_CLUB_ALLIANCE_SEC_REQ NetMsg;
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : Å¬·´ µ¿¸Í Á¦¸í ¿äÃ».
HRESULT GLCharacter::ReqClubAllianceDel ( DWORD dwClubID )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DEL_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DEL_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsAlliance() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DEL_FB_NOTCHIEF") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsChief() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DEL_FB_NOTCHIEF") );
		return S_FALSE;
	}

	if ( m_sCLUB.GetAllBattleNum() > 0 ) 
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DEL_FB_BATTLE") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_ALLIANCE_DEL_REQ NetMsg;
	NetMsg.dwDelClubID = dwClubID;
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : Å¬·´ µ¿¸Í ÇØÃ¼ ¿äÃ».
HRESULT GLCharacter::ReqClubAllianceDis ()
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DIS_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DIS_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsAlliance() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DIS_FB_NOTCHIEF") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsChief() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DIS_FB_NOTCHIEF") );
		return S_FALSE;
	}

	if ( m_sCLUB.GetAllBattleNum() > 0 )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_ALLIANCE_DIS_FB_BATTLE") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_ALLIANCE_DIS_REQ NetMsg;
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : Å¬·´ ¹èÆ² ¿äÃ».
HRESULT GLCharacter::ReqClubBattle ( DWORD dwGaeaID, DWORD dwTime )
{

	if ( !GLCONST_CHAR::bCLUB_BATTLE )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_FAIL") );
		return S_FALSE;
	}

	PGLCHARCLIENT pCHAR_REQ = GLGaeaClient::GetInstance().GetChar ( dwGaeaID );
	if ( !pCHAR_REQ )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_FAIL") );
		return S_FALSE;
	}

	if ( m_sCONFTING.emTYPE != EMCONFT_NONE )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_CONFT") );
		return S_FALSE;		
	}

	DWORD dwCharID = pCHAR_REQ->GetCharData().dwCharID;

	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !pCHAR_REQ->IsClubMaster() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_TARNOTMASTER") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsAllianceGuild( pCHAR_REQ->GETCLUBID() ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_ALLIANCE") );
			
		return S_FALSE;
	}

	if ( m_sCLUB.IsRegDissolution() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_DISSOLUTION") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsBattle( pCHAR_REQ->GETCLUBID() ) || m_sCLUB.IsBattleReady( pCHAR_REQ->GETCLUBID() ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_ALREADY") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsBattleAlliance( pCHAR_REQ->GETALLIANCEID() )  || m_sCLUB.IsBattleReady( pCHAR_REQ->GETALLIANCEID() )  )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_ALREADY2") );
		return S_FALSE;
	}

	if ( dwTime < GLCONST_CHAR::dwCLUB_BATTLE_TIMEMIN )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE
					, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_TIMEMIN")
					, GLCONST_CHAR::dwCLUB_BATTLE_TIMEMIN / 60
					, GLCONST_CHAR::dwCLUB_BATTLE_TIMEMIN % 60);
		return S_FALSE;
	}

	if ( dwTime > GLCONST_CHAR::dwCLUB_BATTLE_TIMEMAX )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE
					, ID2GAMEINTEXT("EMCLUB_BATTLE_REQ_FB_TIMEMAX")
					, GLCONST_CHAR::dwCLUB_BATTLE_TIMEMAX / 60
					, GLCONST_CHAR::dwCLUB_BATTLE_TIMEMAX % 60);
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_BATTLE_REQ NetMsg;
	NetMsg.dwCharID = dwCharID;
	NetMsg.dwBattleTime = dwTime;
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : µ¿¸Í ¹èÆ² ¿äÃ».
HRESULT GLCharacter::ReqAllianceBattle ( DWORD dwGaeaID, DWORD dwTime )
{

	DWORD dwCharID;
	PGLCHARCLIENT pCHAR_REQ;

	if ( !GLCONST_CHAR::bCLUB_BATTLE || !GLCONST_CHAR::bCLUB_BATTLE_ALLIANCE )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_FAIL") );
		return S_FALSE;
	}

	pCHAR_REQ = GLGaeaClient::GetInstance().GetChar ( dwGaeaID );
	if ( !pCHAR_REQ )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_FAIL") );
		return S_FALSE;
	}

	if ( m_sCONFTING.emTYPE != EMCONFT_NONE )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_CONFT") );
		return S_FALSE;		
	}

	dwCharID = pCHAR_REQ->GetCharData().dwCharID;

	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !pCHAR_REQ->IsClubMaster() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_TARNOTMASTER") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsAllianceGuild( pCHAR_REQ->GETCLUBID() ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_ALLIANCE") );
			
		return S_FALSE;
	}

	if ( !pCHAR_REQ->IsAllianceMaster() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_TARNOTMASTER") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsRegDissolution() )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_DISSOLUTION") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsBattle( pCHAR_REQ->GETCLUBID() ) || m_sCLUB.IsBattleReady( pCHAR_REQ->GETCLUBID() ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_ALREADY") );
		return S_FALSE;
	}

	if ( m_sCLUB.IsBattleAlliance( pCHAR_REQ->GETALLIANCEID() ) || m_sCLUB.IsBattleReady( pCHAR_REQ->GETALLIANCEID() ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_ALREADY2") );
		return S_FALSE;
	}

	if ( dwTime < GLCONST_CHAR::dwCLUB_BATTLE_TIMEMIN )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE
					, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_TIMEMIN")
					, GLCONST_CHAR::dwCLUB_BATTLE_TIMEMIN / 60
					, GLCONST_CHAR::dwCLUB_BATTLE_TIMEMIN % 60);
		return S_FALSE;
	}

	if ( dwTime > GLCONST_CHAR::dwCLUB_BATTLE_TIMEMAX )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE
					, ID2GAMEINTEXT("EMALLIANCE_BATTLE_REQ_FB_TIMEMAX")
					, GLCONST_CHAR::dwCLUB_BATTLE_TIMEMAX / 60
					, GLCONST_CHAR::dwCLUB_BATTLE_TIMEMAX % 60);
		return S_FALSE;
	}

	GLMSG::SNET_ALLIANCE_BATTLE_REQ NetMsg;
	NetMsg.dwCharID = dwCharID;
	NetMsg.dwBattleTime = dwTime;
	NETSEND(&NetMsg);

	return S_OK;
}

//	Note : Å¬·´ ¹èÆ² ¿äÃ» ´äº¯.
HRESULT GLCharacter::ReqClubBattleAns ( DWORD dwChiefCharID, bool bOK )
{

	GLMSG::SNET_CLUB_BATTLE_REQ_ANS NetMsgAns;
	NetMsgAns.dwClubCharID = dwChiefCharID;
	NetMsgAns.bOK = bOK;
	NETSEND(&NetMsgAns);

	return S_OK;
}

//	Note : Å¬·´ ¹èÆ² ¿äÃ» ´äº¯.
HRESULT GLCharacter::ReqAllianceBattleAns ( DWORD dwChiefCharID, bool bOK )
{

	GLMSG::SNET_ALLIANCE_BATTLE_REQ_ANS NetMsgAns;
	NetMsgAns.dwClubCharID = dwChiefCharID;
	NetMsgAns.bOK = bOK;
	NETSEND(&NetMsgAns);

	return S_OK;
}

//	Note : Å¬·´ ¹èÆ² ÈÞÀü ¿äÃ».
HRESULT GLCharacter::ReqClubBattleArmistice( DWORD dwCLUBID )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_ARMISTICE_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_ARMISTICE_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsBattle( dwCLUBID ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_ARMISTICE_FB_NOBATTLE") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsBattleStop( dwCLUBID ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_ARMISTICE_FB_DISTIME") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_BATTLE_ARMISTICE_REQ NetMsg;
	NetMsg.dwClubID = dwCLUBID;
	NETSEND(&NetMsg);	

	return S_OK;
}

HRESULT GLCharacter::ReqAllianceBattleArmistice( DWORD dwCLUBID )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_ARMISTICE_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_ARMISTICE_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsAlliance() || !m_sCLUB.IsChief() ) 
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_ARMISTICE_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsBattleAlliance( dwCLUBID ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_ALLIANCE_ARMISTICE_FB_NOBATTLE") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsBattleStop( dwCLUBID ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_ARMISTICE_FB_DISTIME") );
		return S_FALSE;
	}

	GLMSG::SNET_ALLIANCE_BATTLE_ARMISTICE_REQ NetMsg;
	NetMsg.dwClubID = dwCLUBID;
	NETSEND(&NetMsg);	

	return S_OK;
}

//	Note : Å¬·´ ¹èÆ² ÈÞÀü ´äº¯.
HRESULT GLCharacter::ReqClubBattleArmisticeAns( DWORD dwCLUBID, bool bOK )
{	
	GLMSG::SNET_CLUB_BATTLE_ARMISTICE_REQ_ANS NetMsgAns;
	NetMsgAns.dwClubID = dwCLUBID;
	NetMsgAns.bOK = bOK;
	NETSEND(&NetMsgAns);

	return S_OK;
}

//	Note : µ¿¸Í ¹èÆ² ÈÞÀü ´äº¯.
HRESULT GLCharacter::ReqAllianceBattleArmisticeAns( DWORD dwCLUBID, bool bOK )
{	
	GLMSG::SNET_ALLIANCE_BATTLE_ARMISTICE_REQ_ANS NetMsgAns;
	NetMsgAns.dwClubID = dwCLUBID;
	NetMsgAns.bOK = bOK;
	NETSEND(&NetMsgAns);

	return S_OK;
}


//	Note : Å¬·´ ¹èÆ² Ç×º¹ ¿äÃ».
HRESULT GLCharacter::ReqClubBattleSubmission ( DWORD dwCLUBID )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_SUBMISSION_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_SUBMISSION_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsBattle( dwCLUBID ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_SUBMISSION_FB_NOBATTLE") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsBattleStop( dwCLUBID ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMCLUB_BATTLE_SUBMISSION_FB_DISTIME") );
		return S_FALSE;
	}

	GLMSG::SNET_CLUB_BATTLE_SUBMISSION_REQ NetMsg;
	NetMsg.dwClubID = dwCLUBID;
	NETSEND(&NetMsg);	

	return S_OK;
}

//	Note : µ¿¸Í ¹èÆ² Ç×º¹ ¿äÃ».
HRESULT GLCharacter::ReqAllianceBattleSubmission ( DWORD dwCLUBID )
{
	if ( m_dwGuild==CLUB_NULL )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_SUBMISSION_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsMaster(m_dwCharID) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_SUBMISSION_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsAlliance() || !m_sCLUB.IsChief() ) 
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_SUBMISSION_FB_NOTMASTER") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsBattleAlliance( dwCLUBID ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_SUBMISSION_FB_NOBATTLE") );
		return S_FALSE;
	}

	if ( !m_sCLUB.IsBattleStop( dwCLUBID ) )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMALLIANCE_BATTLE_SUBMISSION_FB_DISTIME") );
		return S_FALSE;
	}

	GLMSG::SNET_ALLIANCE_BATTLE_SUBMISSION_REQ NetMsg;
	NetMsg.dwClubID = dwCLUBID;
	NETSEND(&NetMsg);	

	return S_OK;
}

bool GLCharacter::IsKEEP_CLUB_STORAGE ( DWORD dwCHANNEL )
{
	if ( m_sCLUB.m_dwID==CLUB_NULL )		return false;
	if ( m_sCLUB.m_dwMasterID!=m_dwCharID )	return false;

	if ( !m_sCLUB.m_bVALID_STORAGE )		return false;

	if ( m_sCLUB.m_dwRank < dwCHANNEL )		return false;

	return true;
}

//	Note : Ã¢°í Á¤º¸¸¦ ¼­¹ö¿¡ ¿äÃ».
HRESULT GLCharacter::ReqGetClubStorage ()
{
	if ( !IsValidBody() )					return S_FALSE;
	if ( m_sCLUB.m_dwID==CLUB_NULL )		return S_FALSE;
	if ( m_sCLUB.m_dwMasterID!=m_dwCharID )	return S_FALSE;

	//	Note : ÀÌ¹Ì Ã¢°í Á¤º¸°¡ À¯È¿ÇÏ´Ù¸é ¹«½Ã.
	if ( !m_sCLUB.m_bVALID_STORAGE )
	{
		//	Note : ¼­¹ö¿¡ ¿äÃ».
		//		ÀÌ ¸Þ½ÃÁö Àü¼Û ÈÄ ¼­¹öÂÊ¿¡¼­ Å¬·´ÀÇ ¼öÀÍ °»½ÅÀ» ¿äÃ»ÇÑ´Ù.
		GLMSG::SNET_CLUB_GETSTORAGE	NetMsg;
		NETSENDTOFIELD ( &NetMsg );
	}
	else
	{
		//	Note : Å¬·´ÀÇ ¼öÀÍ °»½Å ¿äÃ».
		GLMSG::SNET_CLUB_INCOME_RENEW NetMsgReNew;
		NETSENDTOFIELD ( &NetMsgReNew );
	}

	return S_OK;
}

//	Note : Ã¢°í ¾ÆÀÌÅÛ µé¶§, ³õÀ»¶§, ±³È¯ÇÒ¶§, ÇÕÄ¥¶§.
HRESULT GLCharacter::ReqClubStorageTo ( DWORD dwChannel, WORD wPosX, WORD wPosY )
{
	GASSERT(MAX_CLUBSTORAGE>dwChannel);
	if ( MAX_CLUBSTORAGE<=dwChannel )	return false;

	if ( !IsValidBody() )						return S_FALSE;
	if ( ValidWindowOpen() )					return S_FALSE;	

	if ( !IsKEEP_CLUB_STORAGE(dwChannel) )			return S_FALSE;

	//	°Å·¡ ¿É¼Ç È®ÀÎ
	if ( VALID_HOLD_ITEM () )
	{
		SITEM *pITEM = GLItemMan::GetInstance().GetItem ( GET_HOLD_ITEM().sNativeID );
		if ( !pITEM )		return S_FALSE;
		if ( pITEM )		return S_FALSE;
	
		//	°Å·¡¿É¼Ç
		if ( GET_HOLD_ITEM().bWrap!= true )
		{
		if ( !pITEM->sBasicOp.IsEXCHANGE() )	return S_FALSE;
        if ( GET_HOLD_ITEM().bNonDrop!= false )	return S_FALSE;
		}
	}

	SINVENITEM* pInvenItem = m_sCLUB.m_cStorage[dwChannel].FindPosItem ( wPosX, wPosY );
	if ( !VALID_HOLD_ITEM () && !pInvenItem )		return E_FAIL;

	if ( VALID_HOLD_ITEM () && pInvenItem )
	{
#if defined(VN_PARAM) //vietnamtest%%%
		if ( GET_HOLD_ITEM().bVietnamGainItem  )	return E_FAIL;
#endif
		GLMSG::SNET_CLUB_STORAGE_EX_HOLD NetMsg;
		NetMsg.dwChannel = dwChannel;
		NetMsg.wPosX = pInvenItem->wPosX;
		NetMsg.wPosY = pInvenItem->wPosY;
		NETSENDTOFIELD ( &NetMsg );
	}
	else if ( pInvenItem )
	{
		GLMSG::SNET_CLUB_STORAGE_TO_HOLD NetMsg;
		NetMsg.dwChannel = dwChannel;
		NetMsg.wPosX = pInvenItem->wPosX;
		NetMsg.wPosY = pInvenItem->wPosY;
		NETSENDTOFIELD ( &NetMsg );
	}
	else if ( VALID_HOLD_ITEM () )
	{
#if defined(VN_PARAM) //vietnamtest%%%
		if ( GET_HOLD_ITEM().bVietnamGainItem  )	return E_FAIL;
#endif		

		//	Note : ¸Þ½ÃÁö ¼Û½ÅÀü¿¡ À¯È¿ÇÒÁö¸¦ ¹Ì¸® °Ë»çÇÔ.
		//
		SITEM* pItem = GLItemMan::GetInstance().GetItem ( GET_HOLD_ITEM().sNativeID );
		GASSERT(pItem&&"¾ÆÀÌÅÆ ´ëÀÌÅÍ°¡ Á¸Á¦ÇÏÁö ¾ÊÀ½");

		BOOL bOk = m_sCLUB.m_cStorage[dwChannel].IsInsertable ( pItem->sBasicOp.wInvenSizeX, pItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
		if ( !bOk )
		{
			//	ÀÎ¹êÀÌ °¡µæÂþÀ½.
			return E_FAIL;
		}

		GLMSG::SNET_CLUB_HOLD_TO_STORAGE NetMsg;
		NetMsg.dwChannel = dwChannel;
		NetMsg.wPosX = wPosX;
		NetMsg.wPosY = wPosY;

		NETSENDTOFIELD ( &NetMsg );
	}

	return E_FAIL;
}

bool GLCharacter::IsClubStorageSplitItem ( DWORD dwChannel, WORD wPosX, WORD wPosY )
{
	GASSERT(MAX_CLUBSTORAGE>dwChannel);
	if ( MAX_CLUBSTORAGE<=dwChannel )	return false;

	if ( !IsValidBody() )				return false;
	if ( ValidWindowOpen() )			return false;		
	
	if ( !IsKEEP_CLUB_STORAGE(dwChannel) )			return false;

	SINVENITEM* pInvenItem = m_sCLUB.m_cStorage[dwChannel].FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return false;

	//	Note : ¾ÆÀÌÅÛ Á¤º¸ °¡Á®¿À±â.
	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	GASSERT(pItem&&"¾ÆÀÌÅÆ ´ëÀÌÅÍ°¡ Á¸Á¦ÇÏÁö ¾ÊÀ½");

	bool bSPLIT(false);
	bSPLIT = ( pItem->ISPILE() );
	if ( !bSPLIT )					return false;
	bSPLIT = ( pInvenItem->sItemCustom.wTurnNum>1 );

	return bSPLIT;
}

//	Note : Ã¢°í - °ãÄ§ ¾ÆÀÌÅÛ ºÐ¸®.
HRESULT GLCharacter::ReqClubStorageSplit ( DWORD dwChannel, WORD wPosX, WORD wPosY, WORD wSplitNum )
{
	if ( !IsClubStorageSplitItem(dwChannel,wPosX,wPosY) )	return E_FAIL;

	SINVENITEM* pInvenItem = m_sCLUB.m_cStorage[dwChannel].FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return false;
	
	if ( pInvenItem->sItemCustom.wTurnNum <= wSplitNum )
	{
		return E_FAIL;
	}

	//	Note : ¾ÆÀÌÅÛ ºÐ¸® ¸Þ½ÃÁö Àü¼Û.
	GLMSG::SNET_CLUB_STORAGE_SPLIT NetMsg;
	NetMsg.dwChannel = dwChannel;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NetMsg.wSplit = wSplitNum;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

//	Note : Ã¢°í µ· ³Ö±â.
HRESULT GLCharacter::ReqClubStorageSaveMoney ( LONGLONG lnMoney )
{
	if( m_sCLUB.m_dwID==CLUB_NULL )				return E_FAIL;
	if( m_sCLUB.m_dwMasterID!=m_dwCharID )		return E_FAIL;
	if( !m_sCLUB.m_bVALID_STORAGE )				return E_FAIL;

//#if !defined(KR_PARAM) && !defined(KRT_PARAM)
//	if( m_lnMoney%UINT_MAX < lnMoney )			return E_FAIL;
//#endif
	if ( m_lnMoney < lnMoney )					return E_FAIL;
	if ( ValidWindowOpen() )					return E_FAIL;	

	GLMSG::SNET_CLUB_STORAGE_SAVE_MONEY NetMsg;
	NetMsg.lnMoney = lnMoney;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

//	Note : Ã¢°í µ· »©³»±â.
HRESULT GLCharacter::ReqClubStorageDrawMoney ( LONGLONG lnMoney )
{
	if ( m_sCLUB.m_dwID==CLUB_NULL )			return E_FAIL;
	if ( m_sCLUB.m_dwMasterID!=m_dwCharID )		return E_FAIL;

	if ( !m_sCLUB.m_bVALID_STORAGE )			return E_FAIL;

//#if !defined(KR_PARAM) && !defined(KRT_PARAM)
//	if ( m_sCLUB.m_lnStorageMoney%UINT_MAX < lnMoney )	return E_FAIL;
//#endif
	if ( m_sCLUB.m_lnStorageMoney < lnMoney )	return E_FAIL;
	if ( ValidWindowOpen() )					return E_FAIL;

	GLMSG::SNET_CLUB_STORAGE_DRAW_MONEY NetMsg;
	NetMsg.lnMoney = lnMoney;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqGarbageOpen()
{
	InitGarbageData();
	OpenGarbage();
	
    return S_OK;
}

HRESULT GLCharacter::ReqGarbageMoveItem()
{
	if( m_sPreInventoryItem.VALID() )
	{
		// ¼Õ¿¡ µç ¾ÆÀÌÅÛÀÌ ÀåÂø ¾ÆÀÌÅÛÀÌ¾î¾ß ¹Ù²Ü ¼ö ÀÖ´Ù
		SITEMCUSTOM sPreItem = GET_PREHOLD_ITEM();
		SITEM* pItem = GLItemMan::GetInstance().GetItem( sPreItem.sNativeID );

		// ÆÖÄ«µåÀÏ°æ¿ì
		if ( pItem->sBasicOp.emItemType == ITEM_PET_CARD )
		{
			GLPetClient* pMyPet = GLGaeaClient::GetInstance().GetPetClient ();
			if ( pMyPet && pMyPet->IsVALID () && sPreItem.dwPetID == pMyPet->m_dwPetID )
			{
				m_sPreInventoryItem.RESET();
				return E_FAIL;
			}
		}


		if( pItem && pItem->sBasicOp.IsGarbage() )
		{
			m_sGarbageItem.SET( m_sPreInventoryItem.wPosX, m_sPreInventoryItem.wPosY );
		}
		
		m_sPreInventoryItem.RESET();
	}
	
	return S_OK;
}

HRESULT GLCharacter::ReqGarbageResult()
{
	
	if( !m_sGarbageItem.VALID() )
		return S_FALSE;

	GLMSG::SNET_GARBAGE_RESULT NetMsg;

	NetMsg.wPosX = m_sGarbageItem.wPosX;
	NetMsg.wPosY = m_sGarbageItem.wPosY;
	
	NETSENDTOFIELD( &NetMsg );
	
	return S_OK;
}

HRESULT GLCharacter::ReqGarbageClose()
{
	InitGarbageData();
	CloseGarbage();

	return S_OK;
}

const SITEMCUSTOM& GLCharacter::GET_GARBAGE_ITEM()	// ITEMREBUILD_MARK
{
	static SITEMCUSTOM sItemCustom;
	sItemCustom.sNativeID = NATIVEID_NULL();

	if( !m_sGarbageItem.VALID() )
		return sItemCustom;

	SINVENITEM* pResistItem = m_cInventory.GetItem( m_sGarbageItem.wPosX, m_sGarbageItem.wPosY );
	if( !pResistItem )
		return sItemCustom;

	sItemCustom = pResistItem->sItemCustom;

	return sItemCustom;
}

VOID GLCharacter::InitGarbageData()
{
	m_sGarbageItem.RESET();
	m_sPreInventoryItem.RESET();
}

HRESULT GLCharacter::ReqRebuildOpen()	// ITEMREBUILD_MARK
{
	InitRebuildData();
	OpenRebuild();

	GLMSG::SNET_REBUILD_RESULT NetMsg;

	NetMsg.emResult = EMREBUILD_RESULT_OPEN;

	NETSENDTOFIELD( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqRebuildMoveItem()
{
	GLMSG::SNET_REBUILD_MOVE_ITEM NetMsg;

	if( m_sPreInventoryItem.VALID() )
	{
		// ¼Õ¿¡ µç ¾ÆÀÌÅÛÀÌ ÀåÂø ¾ÆÀÌÅÛÀÌ¾î¾ß ¹Ù²Ü ¼ö ÀÖ´Ù
		SITEMCUSTOM sPreItem = GET_PREHOLD_ITEM();
		SITEM* pItem = GLItemMan::GetInstance().GetItem( sPreItem.sNativeID );
		if( pItem && pItem->sBasicOp.emItemType == ITEM_SUIT )
		{
			// ·£´ý¿É¼Ç ÆÄÀÏÀÌ ÁöÁ¤µÇ¾î ÀÖ¾î¾ß °¡´ÉÇÏ´Ù
			if( strlen( pItem->sRandomOpt.szNAME ) > 3 )
			{
				NetMsg.wPosX = m_sPreInventoryItem.wPosX;
				NetMsg.wPosY = m_sPreInventoryItem.wPosY;
			}
		}
		m_sPreInventoryItem.RESET();
	}

	NETSENDTOFIELD( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqRebuildInputMoney( LONGLONG i64InputMoney )
{
	GLMSG::SNET_REBUILD_INPUT_MONEY NetMsg;

	NetMsg.i64InputMoney = (LONGLONG)max( 0, i64InputMoney );
	NetMsg.i64InputMoney = (LONGLONG)min( NetMsg.i64InputMoney, m_lnMoney );

	NETSENDTOFIELD( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqRebuildResult()
{
	if( !m_sRebuildItem.VALID() )
		return S_FALSE;

	if( m_i64RebuildInput != m_i64RebuildCost || m_lnMoney < m_i64RebuildCost )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT( "EMREBUILD_RESULT_MONEY" ) );
		return E_FAIL;
	}

	GLMSG::SNET_REBUILD_RESULT NetMsg;
	NetMsg.dwNPCID = m_dwNPCID;
	NetMsg.emResult = EMREBUILD_RESULT_SUCCESS;

	NETSENDTOFIELD( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqRebuildClose()
{
	InitRebuildData();

//	Notify( CHAR_REBUILD_ITEM, &m_sRebuildItem );

	CloseRebuild();

	GLMSG::SNET_REBUILD_RESULT NetMsg;

	NetMsg.emResult = EMREBUILD_RESULT_CLOSE;

	NETSENDTOFIELD( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqUsePetCard ( WORD wPosX, WORD wPosY )
{
	if ( !IsValidBody() )						return E_FAIL;

	// ´ë·ÃÁßÀÌ¸é ÆÖÀ» ¼ÒÈ¯ÇÒ ¼ö ¾ø´Ù.
	if ( m_sCONFTING.IsCONFRONTING() )			return E_FAIL;

	PLANDMANCLIENT pLandManClient = GLGaeaClient::GetInstance().GetActiveMap ();
	if ( !pLandManClient )						return E_FAIL;

	GLMapList::FIELDMAP MapsList = GLGaeaClient::GetInstance().GetMapList ();
	GLMapList::FIELDMAP_ITER iter = MapsList.find ( pLandManClient->GetMapID ().dwID );
	if ( iter==MapsList.end() )					return E_FAIL;

	const SMAPNODE *pMapNode = &(*iter).second;

	// ¸ÊÁøÀÔ°¡´É¿©ºÎ Ã¼Å©
	if ( !pMapNode->bPetActivity )
	{
		CInnerInterface::GetInstance().PrintMsgText ( 
						NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("EMPET_USECARD_FB_INVALIDZONE") );
		return E_FAIL;
	}

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )
	{
		// ¾ÆÀÌÅÛ ¾ø´Ù
		return E_FAIL;
	}

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		// ¾ÆÀÌÅÛ ¾ø´Ù
		return E_FAIL;
	}

	if ( pItem->sBasicOp.emItemType != ITEM_PET_CARD )
	{
		// ÆÖÄ«µå ¾Æ´Ï´Ù
		return E_FAIL;
	}
	
	m_llPetCardGenNum	  = pInvenItem->sItemCustom.lnGenNum;
	m_sPetCardNativeID    = pInvenItem->sItemCustom.sNativeID;
	m_cPetCardGenType     = pInvenItem->sItemCustom.cGenType;

	GLMSG::SNETPET_REQ_USEPETCARD NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqReGenPet ()
{
	if ( !IsValidBody() )						return E_FAIL;

	PLANDMANCLIENT pLandManClient = GLGaeaClient::GetInstance().GetActiveMap ();
	if ( !pLandManClient )						return E_FAIL;

	GLMapList::FIELDMAP MapsList = GLGaeaClient::GetInstance().GetMapList ();
	GLMapList::FIELDMAP_ITER iter = MapsList.find ( pLandManClient->GetMapID ().dwID );
	if ( iter==MapsList.end() )					return E_FAIL;

	const SMAPNODE *pMapNode = &(*iter).second;

	if ( !pMapNode->bPetActivity )				return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindItemByGenNumber ( m_llPetCardGenNum, m_sPetCardNativeID, m_cPetCardGenType );
	if ( !pInvenItem ) return E_FAIL;
	
	GLMSG::SNETPET_REQ_USEPETCARD NetMsg;
	NetMsg.wPosX = pInvenItem->wPosX;
	NetMsg.wPosY = pInvenItem->wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

// *****************************************************
// Desc: DB¿¡¼­ »èÁ¦µÈ ÆÖÀÇ Á¤º¸ ¿äÃ»
// *****************************************************
HRESULT	GLCharacter::ReqPetReviveInfo ()
{
	GLMSG::SNETPET_REQ_PETREVIVEINFO NetMsg;
	NetMsg.dwCharID = m_dwCharID;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

// *****************************************************
// Desc: DB¿¡¼­ »èÁ¦µÈ ÆÖ º¹¿ø¿äÃ»
// *****************************************************
HRESULT GLCharacter::ReqPetRevive ( DWORD dwPetID )
{
	GLMSG::SNETPET_REQ_REVIVE NetMsg;
	NetMsg.dwPetID = dwPetID;
	NetMsg.wPosX   = m_wInvenPosX1;
	NetMsg.wPosY   = m_wInvenPosY1;
	NETSENDTOFIELD ( &NetMsg );	

	return S_OK;
}

// *****************************************************
// Desc: ÆÖÄ«µåÀÇ Á¤º¸ ¿äÃ»
// *****************************************************
HRESULT	GLCharacter::ReqPetCardInfo ()
{
	GLInventory::CELL_MAP* pMapItem = m_cInventory.GetItemList();
	GLInventory::CELL_MAP_CITER iter = pMapItem->begin();
	GLInventory::CELL_MAP_CITER iter_end = pMapItem->end();
	for ( ; iter!=iter_end; ++iter )
	{
		SINVENITEM* pInvenItem = (*iter).second;
		SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
		if ( pItem && pItem->sBasicOp.emItemType == ITEM_PET_CARD && pInvenItem->sItemCustom.dwPetID != 0 )
		{
			GLMSG::SNETPET_REQ_PETCARDINFO NetMsg;
			NetMsg.dwPetID = pInvenItem->sItemCustom.dwPetID;
			NETSENDTOFIELD ( &NetMsg );
		}
	}

	for ( WORD i = 0; i < EMSTORAGE_CHANNEL; ++i )
	{
		if ( m_bStorage[i] )
		{
			GLInventory::CELL_MAP* pMapItem = m_cStorage[i].GetItemList();
			GLInventory::CELL_MAP_CITER iter = pMapItem->begin();
			GLInventory::CELL_MAP_CITER iter_end = pMapItem->end();
			for ( ; iter!=iter_end; ++iter )
			{
				SINVENITEM* pInvenItem = (*iter).second;
				SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
				if ( pItem && pItem->sBasicOp.emItemType == ITEM_PET_CARD && pInvenItem->sItemCustom.dwPetID != 0  )
				{
					GLMSG::SNETPET_REQ_PETCARDINFO NetMsg;
					NetMsg.dwPetID = pInvenItem->sItemCustom.dwPetID;
					NETSENDTOFIELD ( &NetMsg );
				}
			}
		}
	}
	
	return S_OK;
}

HRESULT GLCharacter::ReqSetPhoneNumber ( const TCHAR * szPhoneNumber )
{
	if( !szPhoneNumber )	return S_FALSE;

	GLMSG::SNETPC_PHONE_NUMBER NetMsg;

	StringCchCopy ( NetMsg.szPhoneNumber, SMS_RECEIVER, szPhoneNumber );

	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqSendSMS( int nCharID, const TCHAR * szPhoneNumber, const TCHAR * szSmsMsg )
{
	if( !szPhoneNumber )	return S_FALSE;
	if( !szSmsMsg )			return S_FALSE;

	SINVENITEM *pINVENITEM = m_cInventory.FindItem ( ITEM_SMS );
	if ( !pINVENITEM )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMSMS_FB_NO_ITEM") );
		return S_FALSE;
	}

	GLMSG::SNETPC_SEND_SMS NetMsg;

	NetMsg.dwReceiveChaNum = nCharID;
	StringCchCopy ( NetMsg.szPhoneNumber, SMS_RECEIVER, szPhoneNumber );
	StringCchCopy ( NetMsg.szSmsMsg, SMS_LENGTH, szSmsMsg );
	NetMsg.wItemPosX = pINVENITEM->wPosX;
	NetMsg.wItemPosY = pINVENITEM->wPosY;

	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqMGameOddEvenBatting( UINT uiBattingMoney )
{
	// ÃÖ´ë ¹èÆÃ °¡´É ±Ý¾×À» Ã¼Å©ÇÑ´Ù. ¼­¹öÂÊ¿¡¼­µµ Ã¼Å©
	if( uiBattingMoney > MINIGAME_ODDEVEN::uiMaxBattingMoney )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, 
														ID2GAMEINTEXT( "EMMGAME_ODDEVEN_FB_MAXBATTING" ), 
														MINIGAME_ODDEVEN::uiMaxBattingMoney );
		return S_FALSE;
	}

	// º¸À¯±Ý¾×À» Ã¼Å©ÇÑ´Ù. ¼­¹öÂÊ¿¡¼­µµ Ã¼Å©
	if( uiBattingMoney > m_lnMoney )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT( "EMMGAME_ODDEVEN_FB_MONEY_FAIL" ) );
		return S_FALSE;
	}

	// ¹è´ç ±Ý¾×À» ¼ÂÆÃÇØ¼­ ¼­¹ö·Î Àü¼ÛÇÑ´Ù.
	GLMSG::SNETPC_MGAME_ODDEVEN NetMsg;
	NetMsg.dwNPCID = m_dwNPCID;
	NetMsg.emEvent = EMMGAME_ODDEVEN_OK;
	NetMsg.uiBattingMoney = uiBattingMoney;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqMGameOddEvenAgain()
{
	// °ÔÀÌ¸Ó°¡ °ÔÀÓ ¹Ýº¹À» ¿äÃ»

	GLMSG::SNETPC_MGAME_ODDEVEN NetMsg;
	NetMsg.dwNPCID = m_dwNPCID;
	NetMsg.emEvent = EMMGAME_ODDEVEN_AGAIN_OK;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqMGameOddEvenCancel()
{
	// ¼­¹ö·Î °ÔÀÓ Ãë¼Ò¸¦ ¾Ë¸°´Ù.
	GLMSG::SNETPC_MGAME_ODDEVEN NetMsg;
	NetMsg.dwNPCID = m_dwNPCID;
	NetMsg.emEvent = EMMGAME_ODDEVEN_CANCEL;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqMGameOddEvenSelect( BOOL bOdd )
{
	// °ÔÀÌ¸Ó°¡ È¦¼ö, Â¦¼ö¸¦ ¼±ÅÃ
	// bOdd°¡ TRUE¸é È¦¼ö, FALSE¸é Â¦¼ö
	GLMSG::SNETPC_MGAME_ODDEVEN NetMsg;
	NetMsg.dwNPCID = m_dwNPCID;
	NetMsg.emEvent = EMMGAME_ODDEVEN_SELECT;

	if( bOdd )	NetMsg.emCase = EMMGAME_ODDEVEN_ODD;
	else		NetMsg.emCase = EMMGAME_ODDEVEN_EVEN;

	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqMGameOddEvenShuffle()
{
	GLMSG::SNETPC_MGAME_ODDEVEN NetMsg;
	NetMsg.dwNPCID = m_dwNPCID;
	NetMsg.emEvent = EMMGAME_ODDEVEN_SHUFFLE;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqMGameOddEvenFinish()
{
	GLMSG::SNETPC_MGAME_ODDEVEN NetMsg;
	NetMsg.dwNPCID = m_dwNPCID;
	NetMsg.emEvent = EMMGAME_ODDEVEN_FINISH;
	NETSENDTOFIELD(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqFriendWindowOpen( bool bOpen )
{
	GLMSG::SNETPC_REQ_FRIEND_CLUB_OPEN NetMsg;
	NetMsg.emTYPE = EMFRIEND_WINDOW;
	NetMsg.bOpen = bOpen;

	NETSEND( &NetMsg );
	
	return S_OK;
}

HRESULT GLCharacter::ReqClubInfoUpdate( bool bUpdate )
{
	if ( !bUpdate ) 
	{
		if ( CInnerInterface::GetInstance().IsVisibleGroup( CLUB_WINDOW ) || 
			 CInnerInterface::GetInstance().IsVisibleGroup( LARGEMAP_WINDOW ) ) 
			return S_OK;
	}

	GLMSG::SNETPC_REQ_FRIEND_CLUB_OPEN NetMsg;
	NetMsg.emTYPE = EMCLUB_WINDOW;
	NetMsg.bOpen = bUpdate;

	NETSEND( &NetMsg );

	return S_OK;
}


HRESULT GLCharacter::SetVehicle ( bool bActive )
{
	// Ä³¸¯ÅÍÀÇ ÇöÀç »óÅÂ¸¦ Å»°Í¿¡ °ü·ÃÇØ¼­ ÃÊ±âÈ­ ÇÑ´Ù.
 	if ( bActive )
	{
		if ( m_bVehicle ) return E_FAIL;
		m_bVehicle = TRUE;
		m_bReqVehicle = FALSE;
		
		int emType = m_sVehicle.m_emTYPE ;

		if ( emType == VEHICLE_TYPE_BOARD ) //modify vehicle anim
		{
			m_emANISUBTYPE = (EMANI_SUBTYPE) (AN_SUB_HOVERBOARD ) ;  
		}

		if ( !IsSTATE(EM_ACT_PEACEMODE) ) ReqTogglePeaceMode();

		// Å¾½Â ÀÌÆÑÆ® Ãß°¡
		STARGETID sTargetID(CROW_PC, m_dwGaeaID, m_vPos);
		DxEffGroupPlayer::GetInstance().NewEffGroup
		(
			GLCONST_CHAR::strVEHICLE_GEN_EFFECT.c_str(),
			m_matTrans,
			&sTargetID
		);

		SITEMCUSTOM& sItemCustom = m_PutOnItems[SLOT_VEHICLE];

		SITEM* pItem = GLItemMan::GetInstance().GetItem( sItemCustom.sNativeID );
		if ( !pItem ) return E_FAIL;

		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::IDCOLOR, ID2GAMEINTEXT("VEHICLE_SET_FB_OK"), pItem->GetName() );

		DxViewPort::GetInstance().SetVehicleCamera();

		CInnerInterface::GetInstance().SetVehicleDisplay();//add vehicleimage

	}
	else
	{
		if ( !m_bVehicle )	return E_FAIL;

		m_bVehicle = FALSE;		
		m_bReqVehicle = FALSE;

		EMSLOT emRHand = GetCurRHand();
		EMSLOT emLHand = GetCurLHand();
		m_emANISUBTYPE = CHECK_ANISUB ( m_pITEMS[emRHand], m_pITEMS[emLHand]  );

		SITEMCUSTOM& sItemCustom = m_PutOnItems[SLOT_VEHICLE];
		SITEM* pItem = GLItemMan::GetInstance().GetItem( sItemCustom.sNativeID );
		if ( pItem ) CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::IDCOLOR, ID2GAMEINTEXT("VEHICLE_RESET_FB_OK"),pItem->GetName() );
		
		DxViewPort::GetInstance().SetGameCamera();

		CInnerInterface::GetInstance().ResetVehicleDisplay();//add vehicleimage
	}


	//Disable remove buff and Qitem when use vehicle
	// ¹öÇÁ ½ºÅ³ Á¦°Å
	for ( int i = 0; i < SKILLFACT_SIZE; ++i )
	{
		if ( m_sSKILLFACT[i].sNATIVEID == NATIVEID_NULL() ) continue;
		
		PGLSKILL pSkill = GLSkillMan::GetInstance().GetData( m_sSKILLFACT[i].sNATIVEID );

		if ( pSkill && pSkill->m_sBASIC.emIMPACT_SIDE != SIDE_ENERMY )
		{
			FACTEFF::DeleteSkillFactEffect ( STARGETID(CROW_PC,m_dwGaeaID,m_vPos), m_pSkinChar, m_sSKILLFACT[i].sNATIVEID );
			DISABLESKEFF( i );
		}	
	}	

	// Äù¼Ç ¾ÆÀÌÅÛ Á¦°Å
	m_sQITEMFACT.RESET ();
	CInnerInterface::GetInstance().RESET_KEEP_QUESTION_ITEM ();
	

	GLCHARLOGIC::INIT_DATA ( FALSE, FALSE );
	UpdateSuit( TRUE );
	ReSelectAnimation();

	return S_OK;

}

HRESULT GLCharacter::ReqSetVehicle(  bool bActive )
{
	// Å»°ÍÀÌ È°¼ºÈ­
	if ( bActive )
	{
		// ´ë·ÃÁßÀÌ¸é Å»°ÍÀ» È°¼ºÈ­ ½ÃÅ³¼ö ¾ø´Ù.
		if ( m_sCONFTING.IsCONFRONTING() )			return E_FAIL;

		if ( IsACTION(GLAT_ATTACK) || IsACTION(GLAT_SKILL)  || 
			 IsACTION(GLAT_FALLING) || IsACTION(GLAT_TALK) || 
			 IsACTION(GLAT_GATHERING) )	return E_FAIL;

		if ( !m_sVehicle.IsActiveValue() )			return E_FAIL;

		// ¸Ê Ã¼Å© 
		PLANDMANCLIENT pLandManClient = GLGaeaClient::GetInstance().GetActiveMap ();
		if ( !pLandManClient )						return E_FAIL;

		GLMapList::FIELDMAP MapsList = GLGaeaClient::GetInstance().GetMapList ();
		GLMapList::FIELDMAP_ITER iter = MapsList.find ( pLandManClient->GetMapID ().dwID );
		if ( iter==MapsList.end() )					return E_FAIL;

		const SMAPNODE *pMapNode = &(*iter).second;

		// ¸ÊÁøÀÔ°¡´É¿©ºÎ Ã¼Å©
		if ( !pMapNode->bVehicleActivity )
		{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("VEHICLE_SET_FB_MAP_FAIL") );
			return S_OK;
		}


		// ¹ÙÀÌÅ¬ ¼ÒÀ¯ ¿©ºÎ Ã¼Å© 		
		SITEMCUSTOM& sItemCostom = m_PutOnItems[SLOT_VEHICLE];
		if ( sItemCostom.sNativeID == NATIVEID_NULL() )
		{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("VEHICLE_SET_FB_NO_ITEM") );
			return E_FAIL;
		}

		SITEM* pItem = GLItemMan::GetInstance().GetItem( sItemCostom.sNativeID );
		if ( !pItem || pItem->sBasicOp.emItemType != ITEM_VEHICLE )
		{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("VEHICLE_SET_FB_NO_ITEM") );
			return E_FAIL;		
		}

		if ( m_sVehicle.IsNotEnoughFull() )
		{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("VEHICLE_SET_FB_NOTENOUGH_OIL") );
			return E_FAIL;	
		}

		m_sVehicleNativeID  = sItemCostom.sNativeID; //add vehicleimage

		m_bReqVehicle = TRUE;
		
		
		// ¼­¹ö¿¡ ¿äÃ»
		GLMSG::SNETPC_ACTIVE_VEHICLE NetMsg;
		NetMsg.bActive = true;

		NETSENDTOFIELD( &NetMsg );

		return S_OK;
		
	}
	// Å»°ÍÀÌ ºñÈ°¼ºÈ­
	else 
	{
		if ( !m_sVehicle.IsActiveValue() )			return E_FAIL;

		if ( IsACTION(GLAT_ATTACK) || IsACTION(GLAT_SKILL) || IsACTION(GLAT_FALLING) )	return E_FAIL;

		// ¹ÙÀÌÅ¬ ¼ÒÀ¯ ¿©ºÎ Ã¼Å© 
		SITEMCUSTOM& sItemCostom = m_PutOnItems[SLOT_VEHICLE];
		if ( sItemCostom.sNativeID == NATIVEID_NULL() )
		{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("VEHICLE_SET_FB_NO_ITEM") );
			return E_FAIL;
		}

		SITEM* pItem = GLItemMan::GetInstance().GetItem( sItemCostom.sNativeID );
		if ( !pItem || pItem->sBasicOp.emItemType != ITEM_VEHICLE )
		{
			CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::NEGATIVE, ID2GAMEINTEXT("VEHICLE_SET_FB_NO_ITEM") );
			return E_FAIL;		
		}

		// ¼­¹ö¿¡ ¿äÃ»
		GLMSG::SNETPC_ACTIVE_VEHICLE NetMsg;
		NetMsg.bActive = false;

		m_bReqVehicle = TRUE;

		NETSENDTOFIELD( &NetMsg );

		return S_OK;		
		
	}

	return S_OK;
}

HRESULT GLCharacter::ReqVehicleUpdate()
{
	if ( m_sVehicle.IsActiveValue() ) return E_FAIL;

	// ¹ÙÀÌÅ¬ ¼ÒÀ¯ ¿©ºÎ Ã¼Å© 
	SITEMCUSTOM& sItemCostom = m_PutOnItems[SLOT_VEHICLE];
	if ( sItemCostom.sNativeID == NATIVEID_NULL() )
	{
		return E_FAIL;
	}

	GLMSG::SNETPC_GET_VEHICLE NetMsg;
	NetMsg.nItemID = sItemCostom.sNativeID;

	NETSENDTOFIELD( &NetMsg );

	return S_OK;
}

void GLCharacter::ReqVehicleChangeAccessory( EMSUIT emSUIT )
{
	if ( GLTradeClient::GetInstance().Valid() )		return;

	//	Note : °ø°ÝÁßÀÌ³ª ½ºÅ³ ½ÃÀü Áß¿¡ ½½·Ô º¯°æÀ» ¼öÇà ÇÒ ¼ö ¾ø´Ù°í º½.
	//
	if ( IsACTION(GLAT_ATTACK) || IsACTION(GLAT_SKILL) ) return;

	SITEMCUSTOM sHoldItemCustom = GET_HOLD_ITEM ();
	SITEMCUSTOM	sSlotItemCustom = m_sVehicle.GetSlotitembySuittype ( emSUIT );

	//	SLOT <-> HOLD
	if ( sHoldItemCustom.sNativeID != NATIVEID_NULL() && sSlotItemCustom.sNativeID != NATIVEID_NULL() )
	{
		SITEM* pHoldItem = GLItemMan::GetInstance().GetItem ( sHoldItemCustom.sNativeID );
		if ( !pHoldItem ) 
		{
			// ÀÏ¹Ý ¿À·ù
			return;
		}

		if ( !m_sVehicle.CheckSlotItem( pHoldItem->sBasicOp.sNativeID, emSUIT ) ) return;
		
		SITEM* pSlotItem = GLItemMan::GetInstance().GetItem ( sSlotItemCustom.sNativeID );
		if ( !pSlotItem ) 
		{
			// ÀÏ¹Ý ¿À·ù
			return;
		}

		if ( !GLGaeaClient::GetInstance().GetCharacter()->ACCEPT_ITEM ( sHoldItemCustom.sNativeID ) )
		{
			return;
		}

		// Å¸ÀÔÀÌ ´Ù¸£¸é
		if ( pHoldItem->sSuitOp.emSuit != pSlotItem->sSuitOp.emSuit ) return;

		GLMSG::SNET_VEHICLE_REQ_SLOT_EX_HOLD NetMsg;
		NetMsg.emSuit = emSUIT;
		NETSENDTOFIELD ( &NetMsg );
	}
	// HOLD -> SLOT
	else if ( sHoldItemCustom.sNativeID != NATIVEID_NULL() )
	{
		SITEM* pHoldItem = GLItemMan::GetInstance().GetItem ( sHoldItemCustom.sNativeID );
		if ( !pHoldItem ) 
		{
			// ÀÏ¹Ý ¿À·ù
			return;
		}

		if ( !m_sVehicle.CheckSlotItem( pHoldItem->sBasicOp.sNativeID, emSUIT ) ) return;

		if ( !GLGaeaClient::GetInstance().GetCharacter()->ACCEPT_ITEM ( sHoldItemCustom.sNativeID ) )
		{
			return;
		}

		// Å¸ÀÔÀÌ ´Ù¸£¸é
		if ( pHoldItem->sSuitOp.emSuit != emSUIT ) return;

		GLMSG::SNET_VEHICLE_REQ_HOLD_TO_SLOT NetMsg;
		NetMsg.emSuit = emSUIT;
		NETSENDTOFIELD ( &NetMsg );
	}
	// SLOT -> HOLD
	else if ( sSlotItemCustom.sNativeID != NATIVEID_NULL() )
	{
		GLMSG::SNET_VEHICLE_REQ_SLOT_TO_HOLD NetMsg;
		NetMsg.emSuit = emSUIT;
		NETSENDTOFIELD ( &NetMsg );
	}

	return;

}



void GLCharacter::ReqVehicleRemoveSlotItem( EMSUIT emSUIT )
{
	WORD wPosX(0), wPosY(0);
	SITEMCUSTOM	sSlotItemCustom = m_sVehicle.GetSlotitembySuittype ( emSUIT );

	SITEM* pSlotItem = GLItemMan::GetInstance().GetItem ( sSlotItemCustom.sNativeID );
	if ( !pSlotItem ) 
	{
		// ÀÏ¹Ý ¿À·ù
		return;
	}

	BOOL bOk = m_cInventory.FindInsrtable ( pSlotItem->sBasicOp.wInvenSizeX, pSlotItem->sBasicOp.wInvenSizeY, wPosX, wPosY );
	if ( !bOk )
	{
		//	ÀÎ¹êÀÌ °¡µæÂþÀ½.
		return;
	}

	GLMSG::SNET_VEHICLE_REQ_REMOVE_SLOTITEM NetMsg;
	NetMsg.emSuit = emSUIT;

	NETSENDTOFIELD ( &NetMsg );
}

void GLCharacter::ReqVehicleGiveBattery( WORD wPosX, WORD wPosY )
{
	// ºñÈ°¼º »óÅÂ¿¡¼­µµ »ç·á¸¦ ÁÙ ¼ö ÀÖ´Ù.
	//if ( !IsVALID() )							return;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem ) return;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem ) return;

	// ¹ÙÀÌÅ¬ ¾ÆÀÌÅÛ ¿©ºÎ Ã¼Å©
	if ( pItem->sBasicOp.emItemType != ITEM_VEHICLE )	return;

	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pHold ) return;

	// ¿¬·á ¿©ºÎ Ã¼Å©
	if ( pHold->sBasicOp.emItemType != ITEM_VEHICLE_OIL )	return;

	// ¹ÙÀÌÅ¬ Á¤º¸°¡ ¾ø´Ù¸é Ãë¼Ò
	if ( pInvenItem->sItemCustom.dwVehicleID == 0 ) return;



	SVEHICLEITEMINFO sVehicle;


	if ( !DxGlobalStage::GetInstance().IsEmulator() )
	{
		// ¹ÙÀÌÅ¬ ¾ÆÀÌÅÛ Á¤º¸°¡ ¾øÀ¸¸é ¾ø´Â ¹ÙÀÌÅ¬ÀÌ´Ù.
		VEHICLEITEMINFO_MAP_ITER iter = m_mapVEHICLEItemInfo.find ( pInvenItem->sItemCustom.dwVehicleID );
		if ( iter==m_mapVEHICLEItemInfo.end() ) return;
		sVehicle = (*iter).second;
	}
	else
	{
		sVehicle.m_nFull  = m_sVehicle.m_nFull;
		sVehicle.m_emTYPE = m_sVehicle.m_emTYPE;
	}

	// ¿¬·á°¡ °¡µæÂ÷ ÀÖ´Ù¸é
	if ( sVehicle.m_nFull >= 1000 ) return;


	GLMSG::SNET_VEHICLE_REQ_GIVE_BATTERY NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;

	NETSENDTOFIELD ( &NetMsg );
}

void GLCharacter::ReqVehicleInvenUpdate()
{
	GLInventory::CELL_MAP* pMapItem = m_cInventory.GetItemList();
	GLInventory::CELL_MAP_CITER iter = pMapItem->begin();
	GLInventory::CELL_MAP_CITER iter_end = pMapItem->end();
	for ( ; iter!=iter_end; ++iter )
	{
		SINVENITEM* pInvenItem = (*iter).second;
		SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
		if ( pItem && pItem->sBasicOp.emItemType == ITEM_VEHICLE && pInvenItem->sItemCustom.dwVehicleID != 0 )
		{
			GLMSG::SNET_VEHICLE_REQ_ITEM_INFO NetMsg;
			NetMsg.dwVehicleID = pInvenItem->sItemCustom.dwVehicleID;
			NETSENDTOFIELD ( &NetMsg );
		}
	}

	// ¹ÙÀÌÅ¬ ¼ÒÀ¯ ¿©ºÎ Ã¼Å© 
	SITEMCUSTOM& sItemCustom = m_PutOnItems[SLOT_VEHICLE];
	if ( sItemCustom.sNativeID != NATIVEID_NULL() )
	{
		SITEM* pItem = GLItemMan::GetInstance().GetItem ( sItemCustom.sNativeID );
		if ( pItem && pItem->sBasicOp.emItemType == ITEM_VEHICLE && sItemCustom.dwVehicleID != 0 )
		{
			GLMSG::SNET_VEHICLE_REQ_ITEM_INFO NetMsg;
			NetMsg.dwVehicleID = sItemCustom.dwVehicleID;
			NETSENDTOFIELD ( &NetMsg );
		}
	}

	return;

}

void GLCharacter::ReqNonRebirth( BOOL bNonRebirth )
{
	GLMSG::SNET_NON_REBIRTH_REQ NetMsg;
	NetMsg.bNon_Rebirth = bNonRebirth;
	NETSENDTOFIELD ( &NetMsg );
}

void GLCharacter::ReqQBoxEnableState( bool bQboxEnable )
{
	GLMSG::SNET_QBOX_OPTION_REQ_AG NetMsg;
	NetMsg.bQBoxEnable = bQboxEnable;
	NETSEND ( &NetMsg );
}

void GLCharacter::ReqItemShopOpen( bool bOpen )		// ItemShop Open/Close Åëº¸
{
#if defined ( JP_PARAM ) || defined ( _RELEASED)	// JAPAN Item Shop
	
	if ( m_bItemShopOpen == bOpen )	return;
	
	GLMSG::SNETPC_OPEN_ITEMSHOP	NetMsg;
	NetMsg.bOpen = bOpen;
	NETSENDTOFIELD( &NetMsg );
	
	m_bItemShopOpen = bOpen;
	
	CItemShopIconMan* pShopIconMan = CInnerInterface::GetInstance().GetItemShopIconMan();
	if ( !pShopIconMan ) return;

	if ( m_bItemShopOpen )
	{
		pShopIconMan->ADD_SHOP_ICON( m_dwGaeaID );
	}
	else
	{
		pShopIconMan->DEL_SHOP_ICON( m_dwGaeaID );
	}

#endif
	return;
}

HRESULT GLCharacter::ReqAttendList ( bool bDay )
{
	if ( m_bReqAttendList && !bDay )	return S_OK;

	m_bReqAttendList = true;

	//	Note : ¸Þ½ÃÁö Àü¼Û.
	GLMSG::SNETPC_REQ_ATTENDLIST NetMsg;
	NETSEND(&NetMsg);

	return S_OK;
}

HRESULT GLCharacter::ReqAttendance()
{
	//	°ÔÀÓÁ¢¼Ó½Ã°£ Ã¼Å©
	CTime cCurTime = GLGaeaClient::GetInstance().GetCurrentTime();
	CTime cDayTime( cCurTime.GetYear(), cCurTime.GetMonth(), cCurTime.GetDay(),0,0,0 );
	CTime cLoginTime;
	if ( m_tAttendLogin < cDayTime.GetTime()  ) cLoginTime = cDayTime;
	else cLoginTime = m_tAttendLogin;

	CTimeSpan cGameTime = cCurTime - cLoginTime;
	if ( cGameTime.GetTotalMinutes() < m_dwAttendTime ) 
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_ATTEND_FB_ATTENTIME"),m_dwAttendTime );
		return S_FALSE;
	}

	//	Note : ¸Þ½ÃÁö Àü¼Û.
	GLMSG::SNETPC_REQ_ATTENDANCE NetMsg;
	NETSEND(&NetMsg);

	return S_OK;
}


HRESULT GLCharacter::InvenUseTaxiCard( WORD wPosX, WORD wPosY )
{
	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgTextDlg ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMTAXI_TAKE_TICKET") );
		return E_FAIL;
	}

	CInnerInterface::GetInstance().OPEN_TAXI_WINDOW( wPosX, wPosY );

	return S_OK;
}

HRESULT GLCharacter::InvenUseNpcRecall( WORD wPosX, WORD wPosY )
{
	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_NPC_RECALL_FB_NOITEM") );
		return E_FAIL;
	}

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_NPC_RECALL_FB_NOITEM") );
		return E_FAIL;
	}


	//	¼­¹ö ¿äÃ»

	GLMSG::SNET_INVEN_NPC_RECALL NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;
	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqItemMix( DWORD dwNpcID )
{
	SINVENITEM	sInvenItem[ITEMMIX_ITEMNUM];
	for ( int i =0; i < ITEMMIX_ITEMNUM; ++i )
	{
		sInvenItem[i].sItemCustom = GET_ITEM_MIX( i );
		sInvenItem[i].wPosX = m_sItemMixPos[i].wPosX;
		sInvenItem[i].wPosY = m_sItemMixPos[i].wPosY;
	}

	if ( !sInvenItem ) return E_FAIL;


	GLItemMixMan::GetInstance().SortInvenItem( sInvenItem );	

	ITEM_MIX sItemMix;
	for( int i = 0; i < ITEMMIX_ITEMNUM; ++i ) 
	{
		sItemMix.sMeterialItem[i].sNID = GET_ITEM_MIX( i ).sNativeID;
		
		if( sItemMix.sMeterialItem[i].sNID != NATIVEID_NULL() )
			sItemMix.sMeterialItem[i].nNum = GET_ITEM_MIX( i ).wTurnNum;
		if ( sItemMix.sMeterialItem[i].sNID != sInvenItem[i].sItemCustom.sNativeID ) return E_FAIL;
	}

	GLItemMixMan::GetInstance().SortMeterialItem( sItemMix );
	const ITEM_MIX* pItemMix = GLItemMixMan::GetInstance().GetItemMix( sItemMix );
	
	if ( !pItemMix ) 
	{
		//	¾ø´Â ¾ÆÀÌÅÛ Á¶ÇÕ
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMITEM_MIX_FB_NOMIX") );
		CInnerInterface::GetInstance().SetItemMixResult( ID2GAMEINTEXT("EMITEM_MIX_FB_NOMIX") );
		return E_FAIL;
	}

	if ( m_lnMoney < sItemMix.dwPrice ) 
	{
		//	±Ý¾× ¾øÀ½
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMITEM_MIX_FB_NOMONEY") );
		CInnerInterface::GetInstance().SetItemMixResult( ID2GAMEINTEXT("EMITEM_MIX_FB_NOMONEY") );
		return E_FAIL;
	}

	GLMSG::SNET_INVEN_ITEM_MIX sNetMsg;

	sNetMsg.dwKey = pItemMix->dwKey;
	for ( int i = 0; i < ITEMMIX_ITEMNUM; ++i )
	{
		sNetMsg.sInvenPos[i].wPosX = sInvenItem[i].wPosX;
		sNetMsg.sInvenPos[i].wPosY = sInvenItem[i].wPosY;
	}
	sNetMsg.dwNpcID = dwNpcID;

	NETSENDTOFIELD( &sNetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqGathering( const STARGETID& sTargetID )
{
	PLANDMANCLIENT pLAND = GLGaeaClient::GetInstance().GetActiveMap();
	if ( !pLAND )	return E_FAIL;

	{
		//	Note : ¸Þ½ÃÁö ¼Û½ÅÀü¿¡ À¯È¿ÇÒÁö¸¦ ¹Ì¸® °Ë»çÇÔ.
		//
		// »ç¸ÁÈ®ÀÎ
		if ( !IsValidBody() )	return E_FAIL;
		if ( IsACTION( GLAT_GATHERING ) )	return E_FAIL;

		//	°Å¸® Ã¼Å©
		const D3DXVECTOR3 &vTarPos = sTargetID.vPos;

		//	°Å¸® Ã¼Å©
		D3DXVECTOR3 vPos;
		vPos = m_vPos;

		D3DXVECTOR3 vDistance = vPos - vTarPos;
		float fDistance = D3DXVec3Length ( &vDistance );

		WORD wTarBodyRadius = 4;
		WORD wGatherRange = wTarBodyRadius + GETBODYRADIUS() + 2;
		WORD wGatherAbleDis = wGatherRange + 10;

		if ( fDistance>wGatherAbleDis )
		{
			CInnerInterface::GetInstance().PrintMsgText( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_GATHER_FB_DISTANCE") );			
			return E_FAIL;
		}

		if ( sTargetID.emCrow!=CROW_MATERIAL )	
		{
			CInnerInterface::GetInstance().PrintMsgText( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMREQ_GATHER_FB_NOTTYPE") );			
			return E_FAIL;
		}
		
		PGLMATERIALCLIENT pMaterial = pLAND->GetMaterial( sTargetID.dwID );
		if ( !pMaterial )
		{
			CInnerInterface::GetInstance().PrintMsgText( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMGATHER_FB_NOCROW") );			
			return E_FAIL;		
		}

		//	¸Þ½ÃÁö ¹ß»ý.
		GLMSG::SNETPC_REQ_GATHERING NetMsg;
		NetMsg.dwTargetID = sTargetID.dwID;
		NETSENDTOFIELD ( &NetMsg );
	}

	return S_OK;
}

HRESULT GLCharacter::ReqCancelGathering()
{

	CInnerInterface::GetInstance().HideGroup( GATHER_GAUGE );
	return S_OK;
}

HRESULT GLCharacter::ReqWrap( WORD wPosX, WORD wPosY ) // wrapper
{
	if ( !IsValidBody() )						
		return E_FAIL;

	if ( GLTradeClient::GetInstance().Valid() )	
		return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	
		return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			
		return E_FAIL;

	if ( pInvenItem->sItemCustom.IsWrap() )
		return S_FALSE;

	// TODO : What items can only be wrapped 
	if ( pItem->sBasicOp.emItemType != ITEM_SUIT )
		return S_FALSE;

	/*if ( pItem->sBasicOp.emItemType == ITEM_SUIT )
	{
		if ( pItem->sSuitOp.emSuit > SUIT_HANDHELD )
			return S_FALSE;
	}*/

	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pHold )	
		return S_FALSE;

	if ( pHold->sBasicOp.emItemType != ITEM_WRAPPER )	
		return S_FALSE;

	GLMSG::SNET_INVEN_WRAP NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}

HRESULT GLCharacter::ReqRemoveWrap( WORD wPosX, WORD wPosY ) // wrapper
{
	if ( !IsValidBody() )						
		return E_FAIL;

	if ( GLTradeClient::GetInstance().Valid() )	
		return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	
		return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			
		return E_FAIL;

	if ( !pInvenItem->sItemCustom.IsWrap() )
		return S_FALSE;

	GLMSG::SNET_INVEN_REMOVE_WRAP NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}
HRESULT GLCharacter::ReqNonDrop( WORD wPosX, WORD wPosY ) //nondrop card Eduj
{
	if ( !IsValidBody() )						
		return E_FAIL;

	if ( GLTradeClient::GetInstance().Valid() )	
		return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	
		return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			
		return E_FAIL;


	if ( pItem->sBasicOp.emItemType != ITEM_SUIT )
		return S_FALSE;


	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pHold )	
		return S_FALSE;

    bool bNonDrop(false);
	bool bDrop(false);

    if ( pHold->sBasicOp.emItemType == ITEM_NONDROP_CARD )  bNonDrop = true;
    //if ( pHold->sBasicOp.emItemType == ITEM_DROP_CARD ) bDrop = true;
	//if ( !bNonDrop ) return S_FALSE;

	if ( !pItem->sBasicOp.IsTHROW() ||  pInvenItem->sItemCustom.IsNonDrop() )
	{
		if ( bNonDrop )
		{
		  CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "Item is already Nondropable." );
		  return S_FALSE; 
		}
    }



	GLMSG::SNET_INVEN_NONDROP NetMsg;
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}
HRESULT GLCharacter::ReqMaxRV( WORD wPosX, WORD wPosY ) //add max rv card - Eduj
{
	if ( !IsValidBody() )						
		return E_FAIL;

	if ( GLTradeClient::GetInstance().Valid() )	
		return E_FAIL;

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	
		return E_FAIL;

	wPosX = pInvenItem->wPosX;
	wPosY = pInvenItem->wPosY;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )			
		return E_FAIL;

	if ( pItem->sBasicOp.emItemType == ITEM_SUIT )
	   {
		if ( pItem->sSuitOp.emSuit > SUIT_HANDHELD )
		return S_FALSE;
	   }

	SITEM* pHold = GET_SLOT_ITEMDATA ( SLOT_HOLD );
	if ( !pHold )	
		return S_FALSE;

	if ( pHold->sBasicOp.emItemType != ITEM_MAX_RV_CARD )	
		return S_FALSE;

    
	GLMSG::SNET_INVEN_MAXRV NetMsg;
    // check weather the item to be generated to maxrv is attack or defense value
	if (  pItem->sSuitOp.emSuit == SUIT_HEADGEAR ||
 		  pItem->sSuitOp.emSuit == SUIT_UPPER    ||
		  pItem->sSuitOp.emSuit == SUIT_LOWER    ||
		  pItem->sSuitOp.emSuit == SUIT_HAND     ||
	      pItem->sSuitOp.emSuit == SUIT_FOOT )

	{
	if ( (int)GLCONST_CHAR::nMaxRVArmorNum == 1  )
	{
	    if ( pInvenItem->sItemCustom.GETOptTYPE1() != 2 && 
		     pInvenItem->sItemCustom.GETOptTYPE2() != 2 &&
		     pInvenItem->sItemCustom.GETOptTYPE3() != 2 && 
		     pInvenItem->sItemCustom.GETOptTYPE4() != 2  )
	       {
		      CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "No Defense Option to generate." );
              return S_FALSE;
	       }
	}
		NetMsg.bDefense = true; 
  }else{
	if ( (int)GLCONST_CHAR::nMaxRVWeaponNum == 1  )
	{
	     if ( pInvenItem->sItemCustom.GETOptTYPE1() != 1 && 
		      pInvenItem->sItemCustom.GETOptTYPE2() != 1 &&
		      pInvenItem->sItemCustom.GETOptTYPE3() != 1 && 
		      pInvenItem->sItemCustom.GETOptTYPE4() != 1  )
	        {
		       CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "No Attack Option to generate." );
               return S_FALSE;
	        }
	}
		NetMsg.bAttack = true; 
	}
	NetMsg.wPosX = wPosX;
	NetMsg.wPosY = wPosY;

	NETSENDTOFIELD ( &NetMsg );

	return S_OK;
}
HRESULT GLCharacter::ReqInvenSchoolChange ( WORD wSchool ) // Added by mharlon
{
	GLMSG::SNETPC_INVEN_SCHOOL_CHANGE NetMsg;
	NetMsg.wPosX	  = m_wInvenPosX1;
	NetMsg.wPosY	  = m_wInvenPosY1;
	NetMsg.wSchool = wSchool;
	NETSENDTOFIELD ( &NetMsg );

	m_wInvenPosX1 = 0;
	m_wInvenPosY1 = 0;

	return S_OK;
}
//Change School
HRESULT GLCharacter::InvenSchoolChangeSG ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_SCHOOL_CHANGE_FB_NOITEM") );
		return E_FAIL;
	}

	if ( pItem->sBasicOp.emItemType == ITEM_SCHOOL_CHANGE_SG && m_wSchool == 0)
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "You are already in Sacred Gate School" );
		return E_FAIL;
	}


	DoModal( "Are you sure do you want to transfer in Sacred Gate School?", MODAL_INFOMATION, YESNO, MODAL_SCHOOL_CHANGE_SG );

	m_wInvenPosX1 = wPosX;
	m_wInvenPosY1 = wPosY;

	return S_OK;
}
HRESULT GLCharacter::InvenSchoolChangeMP ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_SCHOOL_CHANGE_FB_NOITEM") );
		return E_FAIL;
	}

	if ( pItem->sBasicOp.emItemType == ITEM_SCHOOL_CHANGE_MP && m_wSchool == 1)
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "You are already in Mystic Peak School" );
		return E_FAIL;
	}


	DoModal( "Are you sure do you want to transfer in Mystic Peak School?", MODAL_INFOMATION, YESNO, MODAL_SCHOOL_CHANGE_MP );

	m_wInvenPosX1 = wPosX;
	m_wInvenPosY1 = wPosY;

	return S_OK;
}
HRESULT GLCharacter::InvenSchoolChangePH ( WORD wPosX, WORD wPosY )
{
	//if ( !IsValidBody() )						return E_FAIL;
	//if ( GLTradeClient::GetInstance().Valid() )	return E_FAIL;
	//if( ValidRebuildOpen() )	return E_FAIL;	// ITEMREBUILD_MARK

	SINVENITEM* pInvenItem = m_cInventory.FindPosItem ( wPosX, wPosY );
	if ( !pInvenItem )	return E_FAIL;

	SITEM* pItem = GLItemMan::GetInstance().GetItem ( pInvenItem->sItemCustom.sNativeID );
	if ( !pItem )
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, ID2GAMEINTEXT("EMINVEN_SCHOOL_CHANGE_FB_NOITEM") );
		return E_FAIL;
	}

	if ( pItem->sBasicOp.emItemType == ITEM_SCHOOL_CHANGE_PH && m_wSchool == 2)
	{
		CInnerInterface::GetInstance().PrintMsgText ( NS_UITEXTCOLOR::DISABLE, "You are already in Phoenix School" );
		return E_FAIL;
	}


	DoModal( "Are you sure do you want to transfer in Phoenix School?", MODAL_INFOMATION, YESNO, MODAL_SCHOOL_CHANGE_PH );

	m_wInvenPosX1 = wPosX;
	m_wInvenPosY1 = wPosY;

	return S_OK;
}
void GLCharacter::ReqEventSeekReward( DWORD dwID, SNATIVEID sCrowID )
{
	PCROWDATA pCrowData = GLCrowDataMan::GetInstance().GetCrowData ( sCrowID );
	if ( !pCrowData )	
	{
		CInnerInterface::GetInstance().PrintConsoleText("Not a valid pCrowData.");
		return;
	}

	if ( pCrowData->m_sBasic.sNativeID.wMainID != GLCONST_CHAR::wSEEKCROW_MID && pCrowData->m_sBasic.sNativeID.wSubID != GLCONST_CHAR::wSEEKCROW_SID )
	{
		CInnerInterface::GetInstance().PrintConsoleText("Incorrect NPC ID.");
		return;
	}

	if ( pCrowData->m_emCrow != CROW_NPC )
	{
		CInnerInterface::GetInstance().PrintConsoleText("Not a valid NPC Type.");
		return;
	}

	GLMSG::SNET_GM_EVENT_SEEK_GETREWARD NetMsg;
	NetMsg.dwGaeaID = dwID;
	NetMsg.sCrowID = sCrowID;
	NetMsg.dwGaeaIDChar = GetCtrlID();
	NETSEND(&NetMsg);
}
