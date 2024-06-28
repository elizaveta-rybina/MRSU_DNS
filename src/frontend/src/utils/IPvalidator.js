const NUM_OF_ADDRESS_PARTS = 4;

export function solution() {
  let addrParts = address.split(".");
  if (addrParts.length !== NUM_OF_ADDRESS_PARTS) {
    return false;
  }

  for (let i = 0; i < addrParts.length; i++) {
    let currAddrPart = Number(addrParts[i]);

    if (isNaN(currAddrPart) || currAddrPart < 0 || currAddrPart > 255) {
      return false;
    }
  }

  return true;
}
