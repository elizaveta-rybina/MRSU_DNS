import DescriptionOutlinedIcon from "@mui/icons-material/DescriptionOutlined";
import { useTheme } from "@mui/material";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";
import Modal from "@mui/material/Modal";
import Typography from "@mui/material/Typography";
import * as React from "react";
import { tokens } from "../../theme";
import ZoneFileReader from "../../utils/zonasFile";
const style = {
  position: "absolute",
  top: "50%",
  left: "50%",
  maxWidth: "600px",
  transform: "translate(-50%, -50%)",
  bgcolor: "white",
  border: "1px solid #000",
  borderRadius: 2,
  boxShadow: 20,
  p: 4,
};

export default function BasicModal({ fileInscription, title, subtitle }) {
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  const [open, setOpen] = React.useState(false);
  const openFileZona = () => setOpen(true);
  const closeFileZona = () => setOpen(false);

  return (
    <Box>
      <Button
        color="primary"
        startIcon={<DescriptionOutlinedIcon />}
        onClick={openFileZona}
        sx={{
          margin: "20px 5px",
          border: "1px solid",
          color: colors.grey[100],
        }}
      >
        {fileInscription}
      </Button>
      <Modal
        open={open}
        onClose={closeFileZona}
        aria-labelledby="modal-modal-title"
        aria-describedby="modal-modal-description"
      >
        <Box sx={style}>
          <Box
            sx={{
              display: "flex",
              flexDirection: "row",
              alignItems: "center",
            }}
          >
            <Typography
              id="modal-modal-title"
              variant="h3"
              component="h2"
              color={colors.primary[500]}
              fontWeight={500}
              marginRight="5px"
            >
              {title}
            </Typography>
            <Typography
              id="modal-modal-title"
              variant="h3"
              component="h2"
              color={colors.pinkAccent[500]}
              fontWeight={500}
            >
              {subtitle}
            </Typography>
          </Box>
          <Typography
            id="modal-modal-description"
            variant="code"
            color={colors.primary[500]}
            margin="20px"
          >
            <ZoneFileReader />
          </Typography>
        </Box>
      </Modal>
    </Box>
  );
}
