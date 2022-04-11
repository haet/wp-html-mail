import {
	Popover,
	SlotFillProvider,
	DropZoneProvider,
	navigateRegions,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
// import Notices from 'components/notices';
// import Header from 'components/header';
// import Sidebar from 'components/sidebar';
// import BlockEditor from 'components/block-editor';

function Editor( { settings } ) {
	return (
		<SlotFillProvider>
			<DropZoneProvider>
				<div className="getdavesbe-block-editor-layout">
					{/* <Notices />
					<Header />
					<Sidebar />
					<BlockEditor settings={ settings } /> */}
					hello world! ...
				</div> 
				<Popover.Slot />
			</DropZoneProvider>
		</SlotFillProvider>
	);
}

export default navigateRegions( Editor );