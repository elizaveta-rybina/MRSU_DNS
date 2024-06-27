import CancelIcon from "@mui/icons-material/Close";
import SaveIcon from "@mui/icons-material/Save";
import { GridActionsCellItem } from "@mui/x-data-grid";

const SaveCancelButtons = ({ id, saveClick, cancelClick }) => {
  return (
    <>
      <GridActionsCellItem
        icon={<SaveIcon />}
        label="Save"
        sx={{
          color: "primary.main",
        }}
        onClick={() => saveClick(id)}
      />
      <GridActionsCellItem
        icon={<CancelIcon />}
        label="Cancel"
        className="textPrimary"
        onClick={() => cancelClick(id)}
        color="inherit"
      />
    </>
  );
};

export default SaveCancelButtons;
